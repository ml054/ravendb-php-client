<?php
// TODO
namespace RavenDB\Client\Http;
use CurlHandle;
use PharIo\Manifest\InvalidUrlException;
use Ramsey\Uuid\Uuid;
use RavenDB\Client\Auth\AuthOptions;
use RavenDB\Client\Data\Driver\RavenDB;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Operations\DatabaseHealthCheckOperation;
use RavenDB\Client\Documents\Operations\GetStatisticsOperation;
use RavenDB\Client\Documents\Session\EventDispatcher\Dispatcher;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeRequestEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\FailedRequestEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\SucceedRequestEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\TopologyUpdatedEventArgs;
use RavenDB\Client\Documents\Session\SessionInfo;
use RavenDB\Client\Http\NodeSelector;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Http\Topology;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\ConcurrentHashMap;
use RavenDB\Client\Util\Duration;
use RavenDB\Client\Util\Semaphore;
use RavenDB\Client\Util\ValidatorUtils;

class RequestExecutor implements Closable
{
    use Dispatcher;
    private static $INITIAL_TOPOLOGY_ETAG = -2;
    private string $_useOldFailureCheckOperation;
    public static $requestPostProcessor = null;
    public static $CLIENT_VERSION = "5.0.0";
    protected string $topologyEtag;
    protected string $clientConfigurationEtag;
    protected bool $_disableTopologyUpdates;
    protected bool $_disableClientConfigurationUpdates;
    protected string $lastServerVersion;

    private DocumentConventions $conventions;
    protected ?NodeSelector $_nodeSelector=null;
    private ServerNode $_topologyTakenFromNode;
    private ?Duration $_defaultTimeout;
    private ?Duration $_secondBroadcastAttemptTimeout;
    private ?Duration $_firstBroadcastAttemptTimeout;
    private Duration $_updateTopologyTimer;
    private FailedRequestEventArgs $_onFailedRequest;
    private ?AuthOptions $authOptions;
    private string|null $_databaseName;
    private \DateTime $_lastReturnedResponse;
    private $numberOfServerRequests;
    public function __construct(?DocumentConventions $conventions=null, ?string $databaseName=null, ?AuthOptions $authOptions=null,array $initialUrls = null)
    {
        $this->_databaseName = $databaseName;
        $this->authOptions = $authOptions;
        $this->conventions = $conventions->clone();
        $this->_defaultTimeout = $conventions->getRequestTimeout();
        $this->_secondBroadcastAttemptTimeout = $conventions->getSecondBroadcastAttemptTimeout();
        $this->_firstBroadcastAttemptTimeout = $conventions->getFirstBroadcastAttemptTimeout();
        $this->_lastReturnedResponse = new \DateTime();
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
    private static function globalHttpClientWithCompression(){
        return new ConcurrentHashMap();
    }
    private static function globalHttpClientWithoutCompression(){
        return new ConcurrentHashMap();
    }
    private static function GLOBAL_APPLICATION_IDENTIFIER(): string
    {
        return Uuid::uuid4()->toString();
    }
    private static $configureHttpClient = null;

    private static function backwardCompatibilityFailureCheckOperation():GetStatisticsOperation{
        return new GetStatisticsOperation("failure=check");
    }

    private static function failureCheckOperation():DatabaseHealthCheckOperation{
        return new DatabaseHealthCheckOperation();
    }
    private static function _updateDatabaseTopologySemaphore(){
        return new Semaphore(1);
    }
    private static function _updateClientConfigurationSemaphore(){
        return new Semaphore(1);
    }

    private function _failedNodesTimers():ConcurrentHashMap{
        return new ConcurrentHashMap();
    }
    public function  getTopology():?Topology {
        return $this->_nodeSelector !== null ? $this->_nodeSelector->getTopology() : null;
    }

    public function getUrl():?string{
        if(null === $this->_nodeSelector){
            return null;
        }
        $preferredNode = $this->_nodeSelector->getPreferredNode();
        return $preferredNode !== null ? $preferredNode->currentNode->getUrl() : null;
    }

    /**
     * @return string
     */
    public function getTopologyEtag(): string
    {
        return $this->topologyEtag;
    }

    /**
     * @param string $topologyEtag
     */
    public function setTopologyEtag(string $topologyEtag): void
    {
        $this->topologyEtag = $topologyEtag;
    }




    /**
     * @return string
     */
    public function getClientConfigurationEtag(): string
    {
        return $this->clientConfigurationEtag;
    }

    /**
     * @return string
     */
    public function getLastServerVersion(): string
    {
        return $this->lastServerVersion;
    }

    /**
     * @return Duration
     */
    public function getDefaultTimeout(): Duration
    {
        return $this->_defaultTimeout;
    }

    /**
     * @param Duration $timeout
     */
    public function setDefaultTimeout(Duration $timeout): void
    {
        $this->_defaultTimeout = $timeout;
    }

    /**
     * @return Duration
     */
    public function getSecondBroadcastAttemptTimeout(): Duration
    {
        return $this->_secondBroadcastAttemptTimeout;
    }

    /**
     * @param Duration $secondBroadcastAttemptTimeout
     */
    public function setSecondBroadcastAttemptTimeout(Duration $secondBroadcastAttemptTimeout): void
    {
        $this->_secondBroadcastAttemptTimeout = $secondBroadcastAttemptTimeout;
    }

    /**
     * @return Duration
     */
    public function getFirstBroadcastAttemptTimeout(): Duration
    {
        return $this->_firstBroadcastAttemptTimeout;
    }

    /**
     * @param Duration $firstBroadcastAttemptTimeout
     */
    public function setFirstBroadcastAttemptTimeout(Duration $firstBroadcastAttemptTimeout): void
    {
        $this->_firstBroadcastAttemptTimeout = $firstBroadcastAttemptTimeout;
    }

    /**
     * @throws \Exception
     * Subscribe the Listener
     */
    public function addOnFailedRequestListener(FailedRequestEventArgs $handler){
        $this->add($handler);
    }
    /**
     * @throws \Exception
     * Uns
     */
    public function removeOnFailedRequestListener(FailedRequestEventArgs $handler){
        $this->remove($handler);
    }

    /**
     * @throws \Exception
     */
    public function addOnBeforeRequestListener(BeforeRequestEventArgs $handler){
        $this->add($handler);
    }

    /**
     * @throws \Exception
     */
    public function removeOnBeforeRequestListener(BeforeRequestEventArgs $handler){
        $this->remove($handler);
    }

    /**
     * @throws \Exception
     */
    public function addOnSucceedRequestListener(SucceedRequestEventArgs $handler){
        $this->add($handler);
    }

    public function removeOnSucceedRequestListener(SucceedRequestEventArgs $handler){
        $this->remove($handler);
    }

    public function addOnTopologyUpdatedListener(TopologyUpdatedEventArgs $handler){
        $this->add($handler);
    }

    public function removeOnTopologyUpdatedListener(TopologyUpdatedEventArgs $handler){
        $this->remove($handler);
    }

    public function getConventions():DocumentConventions{
        return $this->conventions;
    }

    public static function create(null|array|string  $initialUrls, ?string $databaseName, ?AuthOptions $authOptions, DocumentConventions $conventions ):self{
        return new RequestExecutor($conventions,$databaseName,$authOptions,$initialUrls);
    }

    public static function createForSingleNodeWithConfigurationUpdates(string $url, string $databaseName, ?AuthOptions $authOptions, DocumentConventions $conventions ):self{

    }

    public static function createForSingleNodeWithoutConfigurationUpdates(string $url, string $databaseName, ?AuthOptions $authOptions, DocumentConventions $conventions ):self{

    }

    /**
     * @throws \Exception
     */
    public static function validateUrls(array $initialUrls, string $certificate=null): array
    {
        return ValidatorUtils::validateUrl($initialUrls,$certificate);
    }

    /*
      * TODO: COMPLETE THE EXECUTE COMMAND*/
    public function execute(RavenCommand $command)
    {
        $this->_executeOnSpecificNode($command,null,null);
    }
    private function createRequest(ServerNode $node, RavenCommand $command): array|null|object
    {
        try {
            $request = $command->createRequest($node);
            if ($request === null) {
                return null;
            }
            return $request;
        } catch (InvalidUrlException $e) {
            throw new \InvalidArgumentException('Unable to parse URL');
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
                if($topology->getEtag()){
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

    private function _sendRequestToServer(
        ServerNode $node, int $nodeIndex,
        RavenCommand $command, bool $shouldRetry,
        array|CurlHandle $request,
        ?SessionInfo $sessionInfo=null,
        ?string $url=null,
        ?string $abortController=null):void
    {
        try {
            $this->numberOfServerRequests++;
            $httpClient = new RavenDB();
            $httpClient->execute($request);
            $command->setResponse($httpClient->getResponse(), false);
        } catch (\Exception $e) {
        }
    }
}
