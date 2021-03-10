<?php

namespace RavenDB\Client\Http;

use InvalidArgumentException;
use PharIo\Manifest\InvalidUrlException;
use RavenDB\Client\Auth\AuthOptions;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Operations\DatabaseHealthCheckOperation;
use RavenDB\Client\Http\Logger\Log;
use RavenDB\Client\Primitives\Closable;

class RequestExecutor implements Closable
{
    private ?AuthOptions $authOptions = null;
    private ?string $_databaseName = null;
    private object $_lastReturnedResponse; // TODO expecting a date object
    private DocumentConventions $conventions;
    private ?int $_defaultTimeout = null;
    private ?int $_secondBroadcastAttemptTimeout = null;
    private ?int $_firstBroadcastAttemptTimeout = null;
    /// how to access
    private static DatabaseHealthCheckOperation $failureCheckOperation;
    public static string $requestPostProcessor; // TODO : Setting Consumer to string
    public static string $CLIENT_VERSION = "5.0.0";
    private Log $logger;
    // private HttpCache $cache; TODO : IMPLEMENTATION PENDING ON GO
    protected NodeSelector $_nodeSelector;  // TODO: CHECK FOR IMPORT
    private static int $INITIAL_TOPOLOGY_ETAG = -2;
    public static string $configureHttpClient;
    private CloseableHttpClient $_httpClient;
    protected float $clientConfigurationEtag;
    private Timer $_updateTopologyTimer;// TODO: TIMER CHECK FOR IMPORT
    protected float $topologyEtag;
    protected bool $_disableTopologyUpdates;
    protected bool $_disableClientConfigurationUpdates;
    protected string $lastServerVersion;
    protected ?string $_firstTopologyUpdate = null; // type to confirm
    protected string $setExec;
    protected object $obj;

    protected function __construct(?string $databaseName = null, ?AuthOptions $authOptions = null, DocumentConventions $conventions, array $initialUrls = null)
    {
        $this->_databaseName = $databaseName;
        $this->authOptions = $authOptions;
        $this->conventions = $conventions->clone();
        $this->_defaultTimeout = $conventions->getRequestTimeout();
        $this->_secondBroadcastAttemptTimeout = $conventions->getSecondBroadcastAttemptTimeout();
        $this->_firstBroadcastAttemptTimeout = $conventions->getFirstBroadcastAttemptTimeout();
    }

    // TODO: CHECK FOR IMPORT --- to migrate - static install : options : A trait with method
    public function getFailureCheckOperation(): DatabaseHealthCheckOperation
    {
        return new DatabaseHealthCheckOperation();
    }

    private static function getLogger(): Log
    {
        //return LogFactory::getLog(RequestExecutor::class);  // TODO : Import a secure package for logging
    }

    public function getTopology(): ?Topology
    {
        return $this->_nodeSelector !== null ? $this->_nodeSelector->getTopology() : null; //TODO MIGRATION : wait on go
    }

    public function getHttpClient(): CloseableHttpClient
    {
        $httpClient = $this->_httpClient;
        if ($httpClient != null) {
            return $httpClient;
        }

        return $this->_httpClient = $this->createHttpClient();
    }

    private function createHttpClient(): HttpClient
    {
        // ConcurrentMap<String, CloseableHttpClient> httpClientCache = getHttpClientCache();

        // $name = getHttpClientName();

        // return httpClientCache.computeIfAbsent(name, n -> createClient());
    } // TODO: CHECK FOR IMPORT

    public function getTopologyEtag(): float
    {
        return $this->topologyEtag;
    }

    public function getClientConfigurationEtag(): float
    {
        return $this->clientConfigurationEtag;
    }

    public static function create(null|array|string $initialUrls = null, ?string $databaseName = null, ?AuthOptions $authOptions = null, DocumentConventions $conventions): self
    {
        return new RequestExecutor($databaseName, $authOptions, $conventions, $initialUrls);
    }

    public static function createForSingleNodeWithConfigurationUpdates(string|array $initialUrls, string $databaseName, AuthOptions $authOptions, DocumentConventions $conventions): self
    {
        //$executor = new createForSingleNodeWithoutConfigurationUpdates($databaseName, $authOptions, $conventions, $initialUrls);
        // TODO MIGRATION TO COMPLETE FROM JVM SOURCE
    }

    public function getSecondBroadcastAttemptTimeout(): int
    {
        return $this->_secondBroadcastAttemptTimeout;
    }

    public function setSecondBroadcastAttemptTimeout(int $secondBroadcastAttemptTimeout): int
    {
        $this->_secondBroadcastAttemptTimeout = $secondBroadcastAttemptTimeout;
    }

    /*
     * TODO: COMPLETE THE EXECUTE COMMAND*/
    public function execute(RavenCommand $command): void
    {
        /* TODO:
         * command: RavenCommand<TResult>,
        sessionInfo?: SessionInfo,
        options?: ExecuteOptions<TResult>): Promise<void> {
        if (options) {
            return this._executeOnSpecificNode(command, sessionInfo, options);
        }
        this._log.info(`Execute command ${command.constructor.name}`);
        const topologyUpdate = this._firstTopologyUpdatePromise;
        const topologyUpdateStatus = this._firstTopologyUpdateStatus;
        if ((topologyUpdate && topologyUpdateStatus.isResolved()) || this._disableTopologyUpdates) {
            const currentIndexAndNode: CurrentIndexAndNode = this.chooseNodeForRequest(command, sessionInfo);
            return this._executeOnSpecificNode(command, sessionInfo, {
                chosenNode: currentIndexAndNode.currentNode,
                nodeIndex: currentIndexAndNode.currentIndex,
                shouldRetry: true
            });
        } else {
            return this._unlikelyExecute(command, topologyUpdate, sessionInfo);
        }
         *
         * TODO : TO BE REMOVED TEMPORARY*/
        $this->_executeOnSpecificNode($command, null, null);
    }

    private function createRequest(ServerNode $node, RavenCommand $command, $url)
    {
        try {
            $request = $command->createRequest($node, $url);
            if ($request === null) {
                return null;
            }
            /* TODO :
             * URI builder = new URI(url.value);

             * if (RequestExecutor.requestPostProcessor) {
                RequestExecutor.requestPostProcessor(req);
            }

            if (command["getRaftUniqueRequestId"]) {
                const raftCommand = command as unknown as IRaftCommand;

                const raftRequestString = "raft-request-id=" + raftCommand.getRaftUniqueRequestId();

                builder = new URL(builder.search ? builder.toString() + "&" + raftRequestString : builder.toString() + "?" + raftRequestString);
            }
            if (this._shouldBroadcast(command)) {
                command.timeout = command.timeout ?? this.firstBroadcastAttemptTimeout;
            }
            req.uri = builder.toString();*/
            return $request;

        } catch (InvalidUrlException $e) {
            throw new InvalidArgumentException('Unable to parse URL');
        }
    }

    // TODO
    public function _executeOnSpecificNode(RavenCommand $command, ?array $sessionInfo = null, ?object $options = null)
    {
        if ($command->failoverTopologyEtag === RequestExecutor::$INITIAL_TOPOLOGY_ETAG) {
            $command->failoverTopologyEtag = RequestExecutor::$INITIAL_TOPOLOGY_ETAG;

            $node = new ServerNode();
            $node->setUrl('http://devtool.infra:9095');
            return $this->createRequest($node, $command, $node->getUrl());
            /*TODO
             * if($this->_nodeSelector && $this->_nodeSelector->getTopology()){
                $topology = $this->_nodeSelector->getTopology();
                if($topology->etag()){
                    $command->failoverTopologyEtag = $this->getTopologyEtag();
                }
            }*/
        }

        /* TODO :
         * if (command.failoverTopologyEtag === RequestExecutor.INITIAL_TOPOLOGY_ETAG) {
               command.failoverTopologyEtag = RequestExecutor.INITIAL_TOPOLOGY_ETAG;

               if (this._nodeSelector && this._nodeSelector.getTopology()) {
                   const topology = this._nodeSelector.getTopology();
                   if (topology.etag) {
                       command.failoverTopologyEtag = topology.etag;
                   }
               }
           }
           const { chosenNode, nodeIndex, shouldRetry } = options;

           this._log.info(`Actual execute ${command.constructor.name} on ${chosenNode.url}`
               + ` ${ shouldRetry ? "with" : "without" } retry.`);
              */

        /* TODO :
        if (!req) {
            return null;
        }

        const controller = new AbortController();

        if (options?.abortRef) {
            options.abortRef(controller);
        }

        req.signal = controller.signal;

        const noCaching = sessionInfo ? sessionInfo.noCaching : false;

        let cachedChangeVector: string;
        let cachedValue: string;
        const cachedItem = this._getFromCache(
            command, !noCaching, req.uri.toString(), (cachedItemMetadata) => {
                cachedChangeVector = cachedItemMetadata.changeVector;
                cachedValue = cachedItemMetadata.response;
            });


        if (cachedChangeVector) {
            if (await this._tryGetFromCache(command, cachedItem, cachedValue)) {
                return;
            }
        }

        this._setRequestHeaders(sessionInfo, cachedChangeVector, req);

        command.numberOfAttempts++;
        const attemptNum = command.numberOfAttempts;
        this._emitter.emit("beforeRequest", new BeforeRequestEventArgs(this._databaseName, url, req, attemptNum));

        let bodyStream: stream.Readable;

        const responseAndStream = await this._sendRequestToServer(chosenNode, nodeIndex, command, shouldRetry, sessionInfo, req, url, controller);

        if (!responseAndStream) {
            return;
        }


        const response = responseAndStream.response;
        bodyStream = responseAndStream.bodyStream;
        const refreshTask = this._refreshIfNeeded(chosenNode, response);

        command.statusCode = response.status;

        let responseDispose: ResponseDisposeHandling = "Automatic";

        try {

            if (response.status === StatusCodes.NotModified) {
                this._emitter.emit("succeedRequest", new SucceedRequestEventArgs(this._databaseName, url, response, req, attemptNum));

                cachedItem.notModified();

                if (command.responseType === "Object") {
                    await command.setResponseFromCache(cachedValue);
                }

                return;
            }

            if (response.status >= 400) {
                const unsuccessfulResponseHandled = await this._handleUnsuccessfulResponse(
                    chosenNode,
                    nodeIndex,
                    command,
                    req,
                    response,
                    bodyStream,
                    req.uri as string,
                    sessionInfo,
                    shouldRetry);

                if (!unsuccessfulResponseHandled) {

                    const dbMissingHeader = response.headers.get(HEADERS.DATABASE_MISSING);
                    if (dbMissingHeader) {
                        throwError("DatabaseDoesNotExistException", dbMissingHeader as string);
                    }

                    this._throwFailedToContactAllNodes(command, req);
                }

                return; // we either handled this already in the unsuccessful response or we are throwing
            }

            this._emitter.emit("succeedRequest", new SucceedRequestEventArgs(this._databaseName, url, response, req, attemptNum));

            responseDispose = await command.processResponse(this._cache, response, bodyStream, req.uri as string);
            this._lastReturnedResponse = new Date();
        } finally {
            if (responseDispose === "Automatic") {
                closeHttpResponse(response);
            }

            await refreshTask;
        }
    }
      * */
    }

    /*
    *     private async _executeOnSpecificNode<TResult>( // this method is called `execute` in c# and java code
       command: RavenCommand<TResult>,
       sessionInfo: SessionInfo = null,
       options: ExecuteOptions<TResult> = null): Promise<void> {

       if (command.failoverTopologyEtag === RequestExecutor.INITIAL_TOPOLOGY_ETAG) {
           command.failoverTopologyEtag = RequestExecutor.INITIAL_TOPOLOGY_ETAG;

           if (this._nodeSelector && this._nodeSelector.getTopology()) {
               const topology = this._nodeSelector.getTopology();
               if (topology.etag) {
                   command.failoverTopologyEtag = topology.etag;
               }
           }
       }

       const { chosenNode, nodeIndex, shouldRetry } = options;

       this._log.info(`Actual execute ${command.constructor.name} on ${chosenNode.url}`
           + ` ${ shouldRetry ? "with" : "without" } retry.`);

       let url: string;
       const req = this._createRequest(chosenNode, command, u => url = u);

       if (!req) {
           return null;
       }

       const controller = new AbortController();

       if (options?.abortRef) {
           options.abortRef(controller);
       }

       req.signal = controller.signal;

       const noCaching = sessionInfo ? sessionInfo.noCaching : false;

       let cachedChangeVector: string;
       let cachedValue: string;
       const cachedItem = this._getFromCache(
           command, !noCaching, req.uri.toString(), (cachedItemMetadata) => {
               cachedChangeVector = cachedItemMetadata.changeVector;
               cachedValue = cachedItemMetadata.response;
           });


       if (cachedChangeVector) {
           if (await this._tryGetFromCache(command, cachedItem, cachedValue)) {
               return;
           }
       }

       this._setRequestHeaders(sessionInfo, cachedChangeVector, req);

       command.numberOfAttempts++;
       const attemptNum = command.numberOfAttempts;
       this._emitter.emit("beforeRequest", new BeforeRequestEventArgs(this._databaseName, url, req, attemptNum));

       let bodyStream: stream.Readable;

       const responseAndStream = await this._sendRequestToServer(chosenNode, nodeIndex, command, shouldRetry, sessionInfo, req, url, controller);

       if (!responseAndStream) {
           return;
       }


       const response = responseAndStream.response;
       bodyStream = responseAndStream.bodyStream;
       const refreshTask = this._refreshIfNeeded(chosenNode, response);

       command.statusCode = response.status;

       let responseDispose: ResponseDisposeHandling = "Automatic";

       try {

           if (response.status === StatusCodes.NotModified) {
               this._emitter.emit("succeedRequest", new SucceedRequestEventArgs(this._databaseName, url, response, req, attemptNum));

               cachedItem.notModified();

               if (command.responseType === "Object") {
                   await command.setResponseFromCache(cachedValue);
               }

               return;
           }

           if (response.status >= 400) {
               const unsuccessfulResponseHandled = await this._handleUnsuccessfulResponse(
                   chosenNode,
                   nodeIndex,
                   command,
                   req,
                   response,
                   bodyStream,
                   req.uri as string,
                   sessionInfo,
                   shouldRetry);

               if (!unsuccessfulResponseHandled) {

                   const dbMissingHeader = response.headers.get(HEADERS.DATABASE_MISSING);
                   if (dbMissingHeader) {
                       throwError("DatabaseDoesNotExistException", dbMissingHeader as string);
                   }

                   this._throwFailedToContactAllNodes(command, req);
               }

               return; // we either handled this already in the unsuccessful response or we are throwing
           }

           this._emitter.emit("succeedRequest", new SucceedRequestEventArgs(this._databaseName, url, response, req, attemptNum));

           responseDispose = await command.processResponse(this._cache, response, bodyStream, req.uri as string);
           this._lastReturnedResponse = new Date();
       } finally {
           if (responseDispose === "Automatic") {
               closeHttpResponse(response);
           }

           await refreshTask;
       }
   }

    * */
    public function close()
    {
        // no instructions yet to setup a content here
    }

    public function getLastServerVersion(): string
    {
        return $this->lastServerVersion;
    }

    public function getDefaultTimeout(): int
    {
        return $this->_defaultTimeout;
    }

    public function setDefaultTimeout(int $timeout): void
    {
        $this->_defaultTimeout = $timeout;
    }

    public function getFirstBroadcastAttemptTimeout(): int
    {
        return $this->_firstBroadcastAttemptTimeout;
    }

    public function setFirstBroadcastAttemptTimeout(int $firstBroadcastAttemptTimeout): void
    {
        $this->_firstBroadcastAttemptTimeout = $firstBroadcastAttemptTimeout;
    }

    private static function staticInit(): void
    {
        RequestExecutor::$failureCheckOperation = new DatabaseHealthCheckOperation();
    }

    public function getConventions(): DocumentConventions
    {
        return $this->conventions;
    }
}