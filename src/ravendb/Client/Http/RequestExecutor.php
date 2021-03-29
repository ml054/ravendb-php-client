<?php

namespace RavenDB\Client\Http;

use DateTime;
use Exception;
use InvalidArgumentException;
use PharIo\Manifest\InvalidUrlException;
use RavenDB\Client\Auth\AuthOptions; // TODO CLASS DESIGNED TO MIGRATE KeyStore, keyPassword, KeyStore FROM JAVA EXECUTOR
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Operations\DatabaseHealthCheckOperation;
use RavenDB\Client\Documents\Session\SessionInfo;
use RavenDB\Client\Http\Logger\Log;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\ValidatorUtils;

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
    private ?int $numberOfServerRequests=null;
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

    public static function createForSingleNodeWithoutConfigurationUpdates(string $url, string $databaseName, AuthOptions $authOptions,DocumentConventions $conventions){
        // final String[] initialUrls = validateUrls(new String[]{url}, certificate); TODO

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
    public function execute(RavenCommand $command)
    {
        $this->_executeOnSpecificNode($command,null,null);
    }

    private function createRequest(ServerNode $node, RavenCommand $command): ?array
    {
        try {
            $request = $command->createRequest($node);
            if ($request === null) {
                return null;
            }

            return $request;
        } catch (InvalidUrlException $e) {
            throw new InvalidArgumentException('Unable to parse URL');
        }
    }
    // TODO MANDATORY this method is called `execute` in c# and java code
    public function _executeOnSpecificNode(RavenCommand $command, ?array $sessionInfo = null, ?object $options = null): void
    {
        if ($command->failoverTopologyEtag === RequestExecutor::$INITIAL_TOPOLOGY_ETAG) {
            $command->failoverTopologyEtag = RequestExecutor::$INITIAL_TOPOLOGY_ETAG;

            /*TODO TO RETRIEVE THE PROPER TOPOLOGIES METHODS*/
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
       $request = $this->createRequest($node,$command);

       if(!$request){
           return;
       }

       $this->_sendRequestToServer($node,0,$command,false,$request,null);
    }

    // TODO : Mandatory : DO NOT CLOSE THE CONNECTION ATTRIBUTES IMPORTED FROM NODEJS. TO CONFIRM IF ALL NEEDED
    private function _sendRequestToServer(ServerNode $node,int $nodeIndex,RavenCommand $command,bool $shouldRetry,
                                          array $request,?SessionInfo $sessionInfo=null, ?string $url=null,?string $abortController=null):void
    {
        $curlUrl = curl_init();
        curl_setopt_array($curlUrl, (array)$request);
        try {
            $this->numberOfServerRequests++;
           // $timeout = $command->getTimeout() || $this->getDefaultTimeout();

            // TODO COMPLETE THE IMPLEMENTATION OF _sendRequestToServer
            $command->setResponse(curl_exec($curlUrl), false);
        } catch (Exception $e) {
        }
    }

    public function close()
    {
        //TODO:  no instructions yet to setup a content here
    }

    public function getLastServerVersion(): string
    {
        return $this->lastServerVersion;
    }

    public function getDefaultTimeout(): ?int
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

    /**
     * @param array $initialUrls
     * @param string|null $certificate
     * @return array
     * @throws Exception
     */
    public static function validateUrls(array $initialUrls, string $certificate=null): array
    {
        return ValidatorUtils::validateUrl($initialUrls,$certificate);
    }
}
