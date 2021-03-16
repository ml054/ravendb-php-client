<?php

namespace RavenDB\Client\Http;

use DateTime;
use InvalidArgumentException;
use PharIo\Manifest\InvalidUrlException;
use RavenDB\Client\Auth\AuthOptions; // TODO CLASS DESIGNED TO MIGRATE KeyStore, keyPassword, KeyStore FROM JAVA EXECUTOR
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Operations\DatabaseHealthCheckOperation;
use RavenDB\Client\Http\Logger\Log;
use RavenDB\Client\Primitives\Closable;

// TODO REMINDER : createRequest method should NEVER send request to server. Request to server should NEVER be closed. Internal process in place
class RequestExecutor implements Closable
{
    private ?array $authOptions = null;
    private ?string $_databaseName = null;
    private DateTime $_lastReturnedResponse;
    private DocumentConventions $conventions;
    private ?int $_defaultTimeout = null;
    private ?int $_secondBroadcastAttemptTimeout = null;
    private ?int $_firstBroadcastAttemptTimeout = null;
    /// how to access
    private static DatabaseHealthCheckOperation $failureCheckOperation;
    public static string $requestPostProcessor;
    public static string $CLIENT_VERSION = "5.0.0";
    private Log $logger;
    // private HttpCache $cache; TODO : IMPLEMENTATION PENDING ON GO
    protected ?NodeSelector $_nodeSelector=null;
    private static int $INITIAL_TOPOLOGY_ETAG = -2;
    public static string $configureHttpClient;
    /* private CloseableHttpClient $_httpClient;*/
    protected float $clientConfigurationEtag;
    private DateTime $_updateTopologyTimer;
    protected float $topologyEtag;
    protected bool $_disableTopologyUpdates;
    protected bool $_disableClientConfigurationUpdates;
    protected string $lastServerVersion;
    protected ?string $_firstTopologyUpdate = null;
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

    // TODO : TASK ASSIGNED AFTER THE CURRENT TEST (GetClusterTopologyTest) SUCCESS -> IMPLEMENT REQUEST TO SERVER
    public function internalExecute()
    {
        /*
         *     public <TResult> void execute(ServerNode chosenNode, Integer nodeIndex, RavenCommand<TResult> command, boolean shouldRetry, SessionInfo sessionInfo, Reference<HttpRequestBase> requestRef) {
        if (command.failoverTopologyEtag == INITIAL_TOPOLOGY_ETAG) {
            command.failoverTopologyEtag = INITIAL_TOPOLOGY_ETAG;
            if (_nodeSelector != null && _nodeSelector.getTopology() != null) {
                Topology topology = _nodeSelector.getTopology();
                if (topology.getEtag() != null) {
                    command.failoverTopologyEtag = topology.getEtag();
                }
            }
        }

        Reference<String> urlRef = new Reference<>();
        HttpRequestBase request = createRequest(chosenNode, command, urlRef);

        if (request == null) {
            return;
        }

        if (requestRef != null) {
            requestRef.value = request;
        }

        //noinspection SimplifiableConditionalExpression
        boolean noCaching = sessionInfo != null ? sessionInfo.isNoCaching() : false;

        Reference<String> cachedChangeVectorRef = new Reference<>();
        Reference<String> cachedValue = new Reference<>();

        try (HttpCache.ReleaseCacheItem cachedItem = getFromCache(command, !noCaching, urlRef.value, cachedChangeVectorRef, cachedValue)) {
            if (cachedChangeVectorRef.value != null) {
                if (tryGetFromCache(command, cachedItem, cachedValue.value)) {
                    return;
                }
            }

            setRequestHeaders(sessionInfo, cachedChangeVectorRef.value, request);

            command.numberOfAttempts = command.numberOfAttempts + 1;
            int attemptNum = command.numberOfAttempts;
            EventHelper.invoke(_onBeforeRequest, this, new BeforeRequestEventArgs(_databaseName, urlRef.value, request, attemptNum));

            CloseableHttpResponse response = sendRequestToServer(chosenNode, nodeIndex, command, shouldRetry, sessionInfo, request, urlRef.value);

            if (response == null) {
                return;
            }

            CompletableFuture<Void> refreshTask = refreshIfNeeded(chosenNode, response);

            command.statusCode = response.getStatusLine().getStatusCode();

            ResponseDisposeHandling responseDispose = ResponseDisposeHandling.AUTOMATIC;

            try {
                if (response.getStatusLine().getStatusCode() == HttpStatus.SC_NOT_MODIFIED) {
                    EventHelper.invoke(_onSucceedRequest, this, new SucceedRequestEventArgs(_databaseName, urlRef.value, response, request, attemptNum));

                    cachedItem.notModified();

                    try {
                        if (command.getResponseType() == RavenCommandResponseType.OBJECT) {
                            command.setResponse(cachedValue.value, true);
                        }
                    } catch (IOException e) {
                        throw ExceptionsUtils.unwrapException(e);
                    }

                    return;
                }

                if (response.getStatusLine().getStatusCode() >= 400) {
                    if (!handleUnsuccessfulResponse(chosenNode, nodeIndex, command, request, response, urlRef.value, sessionInfo, shouldRetry)) {
                        Header dbMissingHeader = response.getFirstHeader("Database-Missing");
                        if (dbMissingHeader != null && dbMissingHeader.getValue() != null) {
                            throw new DatabaseDoesNotExistException(dbMissingHeader.getValue());
                        }

                        throwFailedToContactAllNodes(command, request);
                    }
                    return; // we either handled this already in the unsuccessful response or we are throwing
                }

                EventHelper.invoke(_onSucceedRequest, this, new SucceedRequestEventArgs(_databaseName, urlRef.value, response, request, attemptNum));

                responseDispose = command.processResponse(cache, response, urlRef.value);
                _lastReturnedResponse = new Date();
            } finally {
                if (responseDispose == ResponseDisposeHandling.AUTOMATIC) {
                    IOUtils.closeQuietly(response, null);
                }

                try {
                    refreshTask.get();
                } catch (Exception e) {
                    //noinspection ThrowFromFinallyBlock
                    throw ExceptionsUtils.unwrapException(e);
                }
            }
        }
    }

         * */
    }

    /*
     * TODO: COMPLETE THE EXECUTE COMMAND*/
    public function execute(RavenCommand $command)
    {
        $this->_executeOnSpecificNode($command,null,null);
    }

    /* TODO :
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
    private function createRequest(ServerNode $node, RavenCommand $command): ?array
    {
        try {
            $request = $command->createRequest($node);
            if ($request === null) {
                return null;
            }
            /* TODO :
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
    // TODO MANDATORY this method is called `execute` in c# and java code
    public function _executeOnSpecificNode(RavenCommand $command, ?array $sessionInfo = null, ?object $options = null): string
    {
        if ($command->failoverTopologyEtag === RequestExecutor::$INITIAL_TOPOLOGY_ETAG) {
            $command->failoverTopologyEtag = RequestExecutor::$INITIAL_TOPOLOGY_ETAG;

            /*TODO TO COMPLETE*/
             if($this->_nodeSelector && $this->_nodeSelector->getTopology()){
                $topology = $this->_nodeSelector->getTopology();
                if($topology->etag()){
                    $command->failoverTopologyEtag = $this->getTopologyEtag();
                }
            }
        }
        $node = new ServerNode();
        // TODO : REMOVE HARD CODED ENTRIES
        $node->setUrl('http://devtool.infra:9095');
        return $this->send($node,$command);
    }

    // TODO : Mandatory : DO NOT CLOSE THE CONNECTION
    private function send(ServerNode $node,RavenCommand $command ): string
    {
        $requestOptions = $command->createRequest($node);
        $curlUrl = curl_init();
        curl_setopt_array($curlUrl, $requestOptions);
        return curl_exec($curlUrl);
    }



    // TODO

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
       const req = this._createRequest(chosenNode, command, u => url = u); // TODO : extract the url from

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
        //TODO:  no instructions yet to setup a content here
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