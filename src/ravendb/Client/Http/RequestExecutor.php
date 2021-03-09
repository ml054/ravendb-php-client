<?php

namespace RavenDB\Client\Http;

use Exception;
use HttpRequest;
use RavenDB\Client\Auth\AuthOptions;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Documents\Operations\DatabaseHealthCheckOperation;
use RavenDB\Client\Documents\Session\SessionInfo;
use RavenDB\Client\Http\Logger\Log;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Http\URL;
class RequestExecutor implements Closable
{
    private ?AuthOptions $authOptions=null;
    private ?string $_databaseName=null;
    private object $_lastReturnedResponse; // TODO expecting a date object
    private DocumentConventions $conventions;
    private ?int $_defaultTimeout=null;
    private ?int $_secondBroadcastAttemptTimeout=null;
    private ?int $_firstBroadcastAttemptTimeout=null;
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
    protected ?string $_firstTopologyUpdate=null; // type to confirm
    protected string $setExec;
    protected function __construct(?string $databaseName=null, ?AuthOptions $authOptions=null, DocumentConventions $conventions, array $initialUrls)
    {
        $this->_databaseName = $databaseName;
        $this->authOptions = $authOptions;
        // $this->_lastReturnedResponse = new Date();
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

    private function createHttpClient(): CloseableHttpClient
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

    public static function create(string|array $initialUrls, ?string $databaseName=null, ?AuthOptions $authOptions=null, DocumentConventions $conventions): self
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

    public function execute(ServerNode $chosenNode, RavenCommand $command, &$url):void{
       $command->createRequest($chosenNode,$command, $url);
    }

    private function createRequest(ServerNode $chosenNode, RavenCommand $command, &$url){
        try{
             $request = $command->createRequest($chosenNode, $url);
                if(null === $request){
                    return;
                }
                $builder = new HttpRequest($chosenNode,null,array());
        }catch (Exception $e){

        }
    }
    /*
     * private <TResult> HttpRequestBase createRequest(ServerNode node, RavenCommand<TResult> command, Reference<String> url) {
        try {
            HttpRequestBase request = command.createRequest(node, url);
            if (request == null) {
                return null;
            }
            URI builder = new URI(url.value);

            if (requestPostProcessor != null) {
                requestPostProcessor.accept(request);
            }

            if (command instanceof IRaftCommand) {
                IRaftCommand raftCommand = (IRaftCommand) command;

                String raftRequestString = "raft-request-id=" + raftCommand.getRaftUniqueRequestId();

                builder = new URI(builder.getQuery() != null ? builder.toString() + "&" + raftRequestString : builder.toString() + "?" + raftRequestString);
            }

            if (shouldBroadcast(command)) {
                command.setTimeout(ObjectUtils.firstNonNull(command.getTimeout(), _firstBroadcastAttemptTimeout));
            }

            request.setURI(builder);

            return request;
        } catch (URISyntaxException e) {
            throw new IllegalArgumentException("Unable to parse URL", e);
        }
    }
*/


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