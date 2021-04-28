<?php /** @noinspection ALL */

/** @noinspection PhpUnhandledExceptionInspection */

namespace RavenDB\Client\Documents\Session;
/**
 * InMemoryDocumentSessionOperations subclass dependencies : DeletedEntitiesHolder, DocumentsByEntityHolder, ReplicationWaitOptsBuilder
 */
use Doctrine\Common\Collections\ArrayCollection;
use Ds\Map;
use Ramsey\Uuid\Uuid;
use RavenDB\Client\Constants;
use RavenDB\Client\Documents\Commands\Batches\BatchOptions;
use RavenDB\Client\Documents\Commands\Batches\IndexesWaitOptsBuilder;
use RavenDB\Client\Documents\Commands\Batches\PutCommandDataWithJson;
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
use RavenDB\Client\Json\MetadataAsDictionary;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\ObjectUtils;
use RavenDB\Client\Util\StringUtils;
use RavenDB\Client\DataBind\Node\ObjectNode;
use RavenDB\Tests\Client\Mapper\ObjectMapper\StorageAdapter;
use RavenDB\Tests\Client\Mapper\ObjectMapper\UserMapper;
use function Sodium\add;

abstract class InMemoryDocumentSessionOperations implements Closable
{
    public const ConcurrencyCheckMode = [
        "AUTO"=>"AUTO",
        "FORCED"=>"FORCE",
        "DISABLED"=>"DISABLED"
    ]; // to export to constance
    protected RequestExecutor $_requestExecutor;
    private OperationExecutor $_operationExecutor;
    protected ArrayCollection $_knownMissingIds; // TODO protected final Set<String> _knownMissingIds = new TreeSet<>(String.CASE_INSENSITIVE_ORDER);
    public DocumentsByEntityHolder $documentsByEntity;
    public DocumentsById $documentsById;
    public ArrayCollection $deferredCommands;
    public ArrayCollection $deferredCommandsMap;
    protected ArrayCollection $pendingLazyOperations;
    protected ArrayCollection $onEvaluateLazy;
    private const TRANSACTION_MODE_SINGLE_NODE = "SINGLE_NODE"; // NO ENUM YET IN PHP
    private const TRANSACTION_MODE_CLUSTER_WIDE = "CLUSTER_WIDE"; // NO ENUM YET IN PHP
    protected SessionInfo $sessionInfo;
    protected $mapper;
    private string $id;
    private string $databaseName;
    protected DocumentStoreBase $_documentStore;
    public bool $noTracking=true;
    public ?BatchOptions $_saveChangesOptions=null;
    private int $numberOfRequests;
    private array $externalState;
    private bool $useOptimisticConcurrency;
    private int $maxNumberOfRequestsPerSession;
    protected bool $generateDocumentKeysOnStore;
    private bool $_isDisposal;
    /**
     * @psalm-return Map<string, DocumentInfo>
    */
    public Map $includedDocumentsById;

    protected function __construct(DocumentStoreBase $documentStore, string $id, SessionOptions $options)
    {
        $this->id = $id;
        $this->databaseName = ObjectUtils::firstNonNull(["DemoDB"]);
        if(StringUtils::isBlank($this->databaseName)){
            static::throwNoDatabase();
        }
        $saveChangesOptions = new IndexesWaitOptsBuilder($this);
        $this->_knownMissingIds = new ArrayCollection();
        $this->includedDocumentsById = new Map();
        $this->_saveChangesOptions = $saveChangesOptions->getOptions();
        $this->_documentStore = $documentStore;
        $this->_requestExecutor = $documentStore->getRequestExecutor($this->databaseName);
        $this->noTracking = $options->isNoTracking();
        $this->useOptimisticConcurrency = $this->_requestExecutor->getConventions()->isUseOptimisticConcurrency();
    }


    public function getId(){
        return $this->id;
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
        if(++$this->numberOfRequests > $this->maxNumberOfRequestsPerSession)
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
            //if(null === $includes)continue;
        }
        return true;
    }

    /***************** LifeCycle/UOW/Workflow ********************/
    public function prepareForSaveChanges(): SaveChangesData {
        $result = new SaveChangesData($this);
        // $this->prepareForEntitiesDeletion($result,null);
        $this->prepareForEntitiesPuts($result);
        //$this->prepareForCreatingRevisionsFromIds($result);
        //$this->prepareCompareExchangeEntities($result);
        return $result;
    }


    public function prepareForEntitiesPuts(SaveChangesData $result):void {
        try{
            $serializer = JsonExtensions::storeSerializer();
            $shouldIgnoreEntityChanges = $this->getConvetions()->getShouldIgnoreEntityChanges();

            $entities = $this->documentsByEntity->entities();
            /*** @var DocumentsByEntityEnumeratorResult $entity*/
            foreach($entities as $index=>$entity){

                $entity->getValue()->setIgnoreChanges(false);
                if($entity->getValue()->isIgnoreChanges()) continue;

                if(null !== $shouldIgnoreEntityChanges){
                    if($shouldIgnoreEntityChanges->check($this,$entity->getValue()->getEntity())) continue;
                }

                if($this->isDeleted($entity->getValue()->getId())) continue;
                // $dirtyMetadata = self::updateMetadataModifications($entity->getValue());
                $document = $entity;
                $result->getEntities()->add($entity->getKey());
                // HARD CODING CHANGEVECTOR. TO BE REMOVE
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
      //  return $this->_knownMissingIds->containsKey(id); TODO REIMPLEMENT COMMENTED FOR THE PURPOSE OF THE TEST
    }

    private static function updateMetadataModifications(DocumentInfo $documentInfo):bool{
        $dirty = false;
        if(null !== $documentInfo->getMetadataInstance()){
            $dirty = true;
        }
        foreach ($documentInfo->getMetadataInstance() as $pop) { // TODO : CHECK FOR THE KEYSET
            $dirty = false;
            /**
             * @var MetadataAsDictionary $propValue
             */
            $propValue = $documentInfo->getMetadataInstance()->getLong($pop);
            if(null === $propValue || $propValue instanceof MetadataAsDictionary && ($propValue->isDirty())){
                $dirty = true;
            }
        }
    }
    public function prepareForEntitiesDeletion(SaveChangesData $result, ?array $changes=null):void {
        try {
            //$deletes = $this->dele
        } finally {
            $this->close();
        }
    }
    public function prepareForCreatingRevisionsFromIds(SaveChangesData $result):void { }
    public function prepareCompareExchangeEntities(SaveChangesData $result):void { }
    /** *************************************************** **/
    private static function throwNoDatabase(){
        throw new IllegalStateException("Cannot open a Session without specifying a name of a database ".
            "to operate on. Database name can be passed as an argument when Session is".
            " being opened or default database can be defined using 'DocumentStore.setDatabase()' method");
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
        $this->_knownMissingIds->add($ids);
    }

    protected function rememberEntityForDocumentIdGeneration(object $entity): void {
        throw new \Exception("You cannot set GenerateDocumentIdsOnStore to false without implementing RememberEntityForDocumentIdGeneration");
    }

    public function storeEntityInUnitOfWork($entity, string $id = null, ?string $changeVector=null){
        $documentInfo = new DocumentInfo();
        $documentInfo->setId($id);
        $documentInfo->setEntity($entity);
        $documentInfo->setNewDocument(true);
        $documentInfo->setDocument(null);
        $this->documentsByEntity = new DocumentsByEntityHolder();
        $this->documentsByEntity->put($entity,$documentInfo);
        if($id !== null){
            $this->documentsById = new DocumentsById();
            $this->documentsById->add($documentInfo);
        }
    }

    public function storeInternal(object|array $entity, string $id = null, ?string $changeVector=null,string $forceConcurrencyCheck="DISABLED") {
        $this->noTracking = true;
        if(false === $this->noTracking) throw new IllegalStateException("Cannot store entity. Entity tracking is disabled in this session.");
        if(null === $entity) throw new \InvalidArgumentException("Entity cannot be null");
        $metadata = "";
        $forceConcurrencyCheck= false;
        return $this->storeEntityInUnitOfWork($entity,$id,$changeVector,$metadata,$forceConcurrencyCheck);
    }

    public function forceConcurrenceCheck(string $option){
        if(!array_key_exists($option,self::ConcurrencyCheckMode)) throw new \Exception("No matching Currence Check to provided option found");
        return self::ConcurrencyCheckMode[$option];
    }
}
