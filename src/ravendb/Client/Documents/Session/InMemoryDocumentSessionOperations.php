<?php /** @noinspection ALL */

/**
 * TODO REMOVE ALL PHPSTORM ANNOTATIONS. JUST KEEPING THEM FOR DEV CONCERN
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace RavenDB\Client\Documents\Session;
/**
 * InMemoryDocumentSessionOperations subclass dependencies : DeletedEntitiesHolder, DocumentsByEntityHolder, ReplicationWaitOptsBuilder
 */
use Doctrine\Common\Collections\ArrayCollection;
use Ds\Map;
use Ramsey\Uuid\Uuid;
use RavenDB\Client\Constants;
use RavenDB\Client\Documents\Commands\Batches\BatchCommandResult;
use RavenDB\Client\Documents\Commands\Batches\BatchOptions;
use RavenDB\Client\Documents\Commands\Batches\DeleteCommandData;
use RavenDB\Client\Documents\Commands\Batches\IndexesWaitOptsBuilder;
use RavenDB\Client\Documents\Commands\Batches\PutCommandDataWithJson;
use RavenDB\Client\Documents\Commands\GetDocumentsResult;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\DocumentStoreBase;
use RavenDB\Client\Documents\IDocumentStore;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Operations\SessionOperationExecutor;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Infrastructure\Entities\User;
use RavenDB\Client\Json\JsonOperation;
use RavenDB\Client\Json\MetadataAsDictionary;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\ObjectMapper;
use RavenDB\Client\Util\ObjectUtils;
use RavenDB\Client\Util\StringUtils;
use RavenDB\Client\DataBind\Node\ObjectNode;
use RavenDB\Tests\Client\Mapper\ObjectMapper\StorageAdapter;
use RavenDB\Tests\Client\Mapper\ObjectMapper\UserMapper;
use function Sodium\add;

abstract class InMemoryDocumentSessionOperations implements Closable
{
    use ObjectMapper;
    public const ConcurrencyCheckMode = [
        "AUTO"=>"AUTO",
        "FORCED"=>"FORCE",
        "DISABLED"=>"DISABLED"
    ]; // TODO IMPROVE STATIC CONFIG DATA

    protected RequestExecutor $_requestExecutor;
    private OperationExecutor $_operationExecutor;
    /**
     * @psalm-var Set<String>
    */
    protected ArrayCollection $_knownMissingIds;
    public DocumentsByEntityHolder $documentsByEntity;
    public DocumentsById $documentsById;
    public ArrayCollection $deferredCommands;
    public Map $deferredCommandsMap;
    protected ArrayCollection $pendingLazyOperations;
    protected ArrayCollection $onEvaluateLazy;
    // TODO : IMPLEMENT THE MATCH FUNCTION
    private const TRANSACTION_MODE_SINGLE_NODE = "SINGLE_NODE"; // NO ENUM YET IN PHP
    private const TRANSACTION_MODE_CLUSTER_WIDE = "CLUSTER_WIDE"; // NO ENUM YET IN PHP
    protected SessionInfo $sessionInfo;
    protected $mapper;
    private string $id;
    private string $databaseName;
    protected DocumentStoreBase $_documentStore;
    public bool $noTracking=false;
    public ?BatchOptions $_saveChangesOptions=null;
    private int $numberOfRequests;
    private array $externalState;
    private bool $useOptimisticConcurrency;
    private int $maxNumberOfRequestsPerSession;
    protected bool $generateDocumentKeysOnStore;
    private bool $_isDisposal;
    protected Map $originalContent;
    /**
     * @psalm-return Map<string, DocumentInfo>
     */
    public ArrayCollection $includedDocumentsById;
    public DeletedEntitiesHolder $deletedEntities;

    protected function __construct(DocumentStoreBase $documentStore, string $id, SessionOptions $options)
    {
        $this->id = $id;
        $this->databaseName = $options->getDatabase();
        $this->numberOfRequests = 0;
        $this->maxNumberOfRequestsPerSession=5;

        if(StringUtils::isBlank($this->databaseName)){
            static::throwNoDatabase();
        }

        $this->deferredCommandsMap = new Map();
        $this->includedDocumentsById = new ArrayCollection();
        $this->originalContent = new Map();

        $saveChangesOptions = new IndexesWaitOptsBuilder($this);
        $this->_knownMissingIds = new ArrayCollection();
        $this->documentsById = new DocumentsById();
        $this->documentsByEntity = new DocumentsByEntityHolder();
        $this->deletedEntities = new DeletedEntitiesHolder();

        $this->_saveChangesOptions = $saveChangesOptions->getOptions();
        $this->_documentStore = $documentStore;
        $this->_requestExecutor = $documentStore->getRequestExecutor($this->databaseName);
        $this->noTracking = $options->isNoTracking();
        $this->useOptimisticConcurrency = $this->_requestExecutor->getConventions()->isUseOptimisticConcurrency();
    }


    public function getNumberOfEntitiesInUnitOfWork(){
        return $this->documentsByEntity->size();
    }

    public function getId(){
        return $this->id;
    }

    public function registerIncludes(object $includes){
        if($this->noTracking || null === $includes) return;
    }

    public function getCurrentSessionNode():ServerNode {
        return $this->getSessionInfo()->getCurrentSessionNode($this->_requestExecutor);
    }
    /**
     * @return bool
     */
    public function isUseOptimisticConcurrency(): bool
    {
        return $this->useOptimisticConcurrency;
    }

    /**
     * @param bool $useOptimisticConcurrency
     */
    public function setUseOptimisticConcurrency(bool $useOptimisticConcurrency): void
    {
        $this->useOptimisticConcurrency = $useOptimisticConcurrency;
    }

    protected function entityChanged(?object $newObject=null, ?DocumentInfo $documentInfo=null, ?ArrayCollection $changes=null){
        JsonOperation::entityChanged($newObject,$documentInfo,$changes);
    }
    /**
     * @return int
     */
    public function getMaxNumberOfRequestsPerSession(): int
    {
        return $this->maxNumberOfRequestsPerSession;
    }

    /**
     * @param int $maxNumberOfRequestsPerSession
     */
    public function setMaxNumberOfRequestsPerSession(int $maxNumberOfRequestsPerSession): void
    {
        $this->maxNumberOfRequestsPerSession = $maxNumberOfRequestsPerSession;
    }

    public function incrementRequestCount(){
        if( ++$this->numberOfRequests > $this->maxNumberOfRequestsPerSession)
            throw new \Exception(StringUtils::format(Constants::EXCEPTION_STRING_NUMBER_OF_REQUESTS,$this->maxNumberOfRequestsPerSession));
    }

    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    public function getExternalState(){
        if(null === $this->externalState){
            $this->externalState = new ArrayCollection();
        }
        return $this->externalState;
    }

    public function getConvetions():DocumentConventions {
        return $this->_requestExecutor->getConventions();
    }

    public function getDocumentId(object $instance):string|null {
        if(null === $instance) return null;
    }

    public function checkIfIdAlreadyIncluded(array $ids,?ArrayCollection $includes=null):bool {
        foreach($ids as $id){
            if($this->_knownMissingIds->contains($id)) continue;
            $documentInfo = $this->documentsById->getValue($id);
            if(null === $documentInfo){
                $documentInfo = $this->includedDocumentsById->get($id);
                if(null === $documentInfo)return false;
            }
            if (null === $documentInfo->getEntity() && null === $documentInfo->getDocument()) return false;
        }
        return true;
    }

    /***************** LifeCycle/UOW/Workflow ********************/
    public function prepareForSaveChanges(): SaveChangesData {

        $result = new SaveChangesData($this);
       $this->prepareForEntitiesDeletion($result,null);
       $this->prepareForEntitiesPuts($result);
        //$this->prepareForCreatingRevisionsFromIds($result);
        //$this->prepareCompareExchangeEntities($result);
        return $result;
    }

    protected function updateSessionAfterSaveChanges(BatchCommandResult $result){
        $returnedTransactionIndex = $result->getTransactionIndex();
    }

    public function prepareForEntitiesPuts(SaveChangesData $result):void {
        try{
            $shouldIgnoreEntityChanges = $this->getConvetions()->getShouldIgnoreEntityChanges();

            $entities = $this->documentsByEntity->entities();

            /*** @var DocumentsByEntityEnumeratorResult $entity */
            foreach($entities as $index=>$entity){

                $entity->getValue()->setIgnoreChanges(false);
                if($entity->getValue()->isIgnoreChanges()) continue;

                if(null !== $shouldIgnoreEntityChanges){
                    if($shouldIgnoreEntityChanges->check($this,$entity->getValue()->getEntity())) continue;
                }

                if($this->isDeleted($entity->getValue()->getId())) continue;
                $document = $entity;
                $result->getEntities()->add($entity->getKey());
                $result->getSessionCommands()->add(new PutCommandDataWithJson($entity->getValue()->getId(),'PA-Test',$document,"NONE"));
            }
        } finally {
            $this->close();
        }
    }

    /**
     * Returns whether a document with the specified id is deleted
     * or known to be missing
     *
     * @param id Document id to check
     * @return true is document is deleted
     */
    public function isDeleted(string $id) {
        //  return $this->_knownMissingIds->containsKey(id);
    }

    private static function updateMetadataModifications(DocumentInfo $documentInfo):bool{
        $dirty = false;
        if(null !== $documentInfo->getMetadataInstance()){
            $dirty = true;
        }
        foreach ($documentInfo->getMetadataInstance() as $pop) {
            $dirty = false;
            /*** @var MetadataAsDictionary $propValue */
            $propValue = $documentInfo->getMetadataInstance()->getLong($pop);
            if(null === $propValue || $propValue instanceof MetadataAsDictionary && ($propValue->isDirty())){
                $dirty = true;
            }
        }
    }
    public function ignoreChangesFor(object $entity):void{

    }


    protected function assertNoNonUniqueInstance(object $entity, string $id):void{
        $info = $this->documentsById->getValue($id);
        if(null === $info || $info->getEntity() === $entity){
            return;
        }
        throw new \Exception("Attempted to associate a different object with id '" . $id ."'.");
    }
    private function getDocumentInfo($instance){
        $documentInfo = $this->documentsByEntity->get($instance);
        if(null !== $documentInfo) return $documentInfo;
        return null;
    }

    public function getMetadataFor ($instance){
        if(null === $instance) throw new \InvalidArgumentException("Instance cannot be null");
        /**
         * @var DocumentInfo $documentInfo
        */
        $documentInfo = $this->getDocumentInfo($instance);
        if(null !== $documentInfo->getMetadataInstance()){
            return $documentInfo->getMetadataInstance();
        }

        $metadataAsJson = $documentInfo->getMetadata();
        $metadata = new MetadataAsDictionary($metadataAsJson);
        $documentInfo->setMetadataInstance($metadata);

        return $metadata;
    }

    /**
     * @psalm-param Map<string, list<DocumentsChanges>> $changes
     */
    public function prepareForEntitiesDeletion(SaveChangesData $result, ?Map $changes=null):void {
        try {
            foreach($this->deletedEntities as  $deletedEntity){
                $documentInfo = $this->documentsByEntity->get($this->deletedEntities->getEntity());
                if(null === $documentInfo) continue;

                if(null !== $changes){
                    $docChanges = new ArrayCollection();
                    $change = new DocumentsChanges();
                    $change->setFieldNewValue("");
                    $change->setFieldOldValue("");
                    $change->setChange("DOCUMENT_DELETED");
                    $docChanges->add($change);
                    $changes->put($documentInfo->getId(),$docChanges);
                }else{
                    $command = null;
                    if(null !== $command){
                        throw new \Exception("throwInvalidDeletedDocumentWithDeferredCommand");
                    }
                    $changeVector = null;
                    $documentInfo = $this->documentsById->getValue($documentInfo->getId());
                    if (null !== $documentInfo){
                        $changeVector = $documentInfo->getChangeVector();
                        if(null !== $documentInfo->getEntity()){
                            $result->getEntities()->add($documentInfo->getEntity());
                        }
                    }
                    $changeVector = $this->useOptimisticConcurrency ? $changeVector : null;
                    $result->getSessionCommands()->add(new DeleteCommandData($documentInfo->getId(),$changeVector));
                }
            }
        } finally {
            $this->close();
        }
    }
    public function refreshInternal(object $entity, GetDocumentsResult $cmd, DocumentInfo $documentInfo){
        $document = $cmd->getResult()->getResults()[0];
        if(null === $document) throw new \InvalidArgumentException("Document '" . $documentInfo->getId() . "' no longer exists and was probably deleted");

        $docInfoToArray = JsonExtensions::storeSerializer()->normalize(($documentInfo),'json');
        $value = $docInfoToArray[Constants::METADATA_KEY];
        $documentInfo->setMetadata($value);

        if(null !== $documentInfo){
            $changeVector = $value[Constants::METADATA_CHANGE_VECTOR];
            $documentInfo->setChangeVector($changeVector);
        }

        if(null !== $documentInfo->getEntity() && !$this->noTracking){
            // TODO
        }

        dd($docInfoToArray);
    }


    public function prepareForCreatingRevisionsFromIds(SaveChangesData $result):void { }
    public function prepareCompareExchangeEntities(SaveChangesData $result):void { }
    /**
     * Tracks the entity inside the unit of work
     * @param entityType Entity class
     * @param id         Id of document
     * @param document   raw entity
     * @param metadata   raw document metadata
     * @param noTracking no tracking
     * @return entity
     */
    public function trackEntity(string $entityType, object $document, ?string $id=null, ?object $metadata=null, ?bool $noTracking=null)
    {
        $noTracking = $this->noTracking || $noTracking;
        if(empty($id)){
            throw new \Exception("TODO ".__METHOD__);
        }
        $docInfo = $this->documentsById->getValue($id);
        if(null !== $docInfo){

            // the local instance may have been changed, we adhere to the current Unit of Work
            // instance, and return that, ignoring anything new.

            if(null === $docInfo->getEntity() && null !== $document->getDocument()){

                $normalize = JsonExtensions::storeSerializer()->serialize($document->getDocument(),'json');
                $deserialize = JsonExtensions::storeSerializer()->deserialize($normalize,$entityType,'json');
                return $deserialize;
            }

            if(!$noTracking){
                $this->includedDocumentsById->remove($id);
                $this->documentsByEntity->put($docInfo->getEntity(),$docInfo);
            }

            return $docInfo->getEntity();
        }

        $docInfo = $this->includedDocumentsById->get($id);

        if(null !== $docInfo){
            if(null === $docInfo->getEntity()){
                dd(__METHOD__,$entityType,$id,$document);
            }
            if(!$this->noTracking){
                $this->includedDocumentsById->remove($id);
                $this->documentsById->add($docInfo);
                $this->documentsByEntity->put($docInfo->getEntity(),$docInfo);
            }
            return $docInfo->getEntity();
        }

    }
    /** *************************************************** **/
    private static function throwNoDatabase(){
        throw new IllegalStateException(Constants::EXCEPTION_STRING_NO_SESSION_DATABASE);
    }

    public function getDocumentStore():IDocumentStore|ArrayCollection {
        return $this->_documentStore;
    }

    public function getRequestExecutor():RequestExecutor {
        return $this->_requestExecutor;
    }

    public function getSessionInfo(): SessionInfo {
        return $this->sessionInfo;
    }

    public function getOperations():OperationExecutor {
        if(null === $this->_operationExecutor){
            $this->_operationExecutor = new SessionOperationExecutor($this);
        }
    }

    public function getNumberOfRequests(): int
    {
        return $this->numberOfRequests;
    }

    /**
     * @return int
     */
    public function getDeferredCommands(): int
    {
        return count($this->deferredCommands);
    }


    public function registerMissing(array $ids):void {
        if ($this->noTracking) {
            return;
        }
        $this->_knownMissingIds->putAll($ids);
    }

    protected function rememberEntityForDocumentIdGeneration(object $entity): void {
        throw new \Exception(Constants::EXCEPTION_STRING_ID_GENERATOR);
    }
    public function storeEntityInUnitOfWork($entity, string $id = null, ?string $changeVector=null){

        if(null !== $id){
            $this->_knownMissingIds->add($id);
        }

        $documentInfo = new DocumentInfo();
        $documentInfo->setId($id);
        $documentInfo->setEntity($entity);
        $documentInfo->setNewDocument(true);
        $documentInfo->setDocument(null);
        $document = $this->documentsByEntity->get($entity);

        $this->documentsByEntity->put($entity,$documentInfo);

        if($id !== null){
            $this->documentsById->add($documentInfo);
        }
    }

    public function storeInternal(object|array $entity, string $id = null, ?string $changeVector=null,string $forceConcurrencyCheck="DISABLED"):void {
        $this->noTracking = false;
        if(true === $this->noTracking) throw new IllegalStateException(Constants::EXCEPTION_STRING_NO_TRACKING);
        if(null === $entity) throw new \InvalidArgumentException(Constants::EXCEPTION_STRING_EMTPY_ENTITY);

        $metadata = "";
        $value = $this->documentsByEntity->get($entity);

        /*if(null !== $value){
            $value->setChangeVector(ObjectUtils::firstNonNull([$changeVector]));
            $value->setConcurrencyCheckMode($forceConcurrencyCheck);
            return;
        }*/


        if(null === $id){
            if($this->generateDocumentKeysOnStore){
                throw new \Exception("TODO MIGRATE THE GENERATOR"); // TODO CHECK WITH AREK
            }else{
                $this->rememberEntityForDocumentIdGeneration($entity);
            }
        }else{
            // JAVA SOURCE :  generateEntityIdOnTheClient.trySetIdentity(entity, id);
        }
        // if($this->deletedEntities->contains($entity)) throw new \Exception("Can't store object, it was already deleted in this session. Document id: " . $id);
        $this->storeEntityInUnitOfWork($entity,$id,$changeVector,$metadata,$forceConcurrencyCheck);
    }

    public function forceConcurrenceCheck(string $option){
        if(!array_key_exists($option,self::ConcurrencyCheckMode)) throw new \Exception(Constants::EXCEPTION_STRING_INVALID_OPTION);
        return self::ConcurrencyCheckMode[$option];
    }
    /**
     * Determines whether the specified entity has changed.
     * @param entity Entity to check
     * @return true if entity has changed
     */
    public function hasChanged(object $entity): bool {
        $documentInfo = $this->documentsByEntity->get($entity);
        if(null === $documentInfo) return false;
        // Serialize
    }
    /// CACHING METHOD FOR NOW OUT OF PHP MIGRATION SCOPE
    public function registerCounters(object $resultCounters, array $ids, array $countersToInclude, bool $gotAll):void {
        if($this->noTracking) return;
        if(null === $resultCounters || 0 === count($resultCounters)){
            if(true === $gotAll){
                foreach($ids as $id){
                    $this->setGotAllCountersForDocument($id);
                }
                return;
            }
        }else{
            $this->registerCountersInternal($resultCounters);
        }
    }
}
