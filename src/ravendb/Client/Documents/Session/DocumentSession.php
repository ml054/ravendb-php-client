<?php /** @noinspection ALL */

/** @noinspection PhpUnhandledExceptionInspection */

namespace RavenDB\Client\Documents\Session;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Ds\Map;
use RavenDB\Client\Constants;
use RavenDB\Client\Documents\Batches\ICommandData;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Documents\Linq\IDocumentQuery;
use RavenDB\Client\Documents\Linq\IDocumentQueryGenerator;
use RavenDB\Client\Documents\Session\Loaders\ILoaderWithInclude;
use RavenDB\Client\Documents\Session\Operations\BatchOperation;
use RavenDB\Client\Documents\Session\Operations\LoadOperation;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Exceptions\RavenException;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Util\ObjectMapper;
use RavenDB\Client\Util\StringUtils;
use \Swaggest\JsonDiff;
use \TreeWalker;
class DocumentSession extends InMemoryDocumentSessionOperations
    implements IDocumentSessionImpl,IAdvancedSessionOperations,IDocumentQueryGenerator
{
    use ObjectMapper;
    private DocumentStore $documentStore;
    private string $id;
    private SessionOptions $options;
    private ArrayCollection $externalState;
    private int $getMaxNumberOfRequestsPerSession;
    private bool $useOptimisticConcurrency;
    private ArrayCollection $changes;
    private Map $container;
    public function __construct(DocumentStore $documentStore, string $id, SessionOptions $options)
    {
        $this->documentStore = $documentStore;
        $this->id = $id;
        $this->options = $options;
        $this->container = new Map();
        parent::__construct($documentStore,$id,$options);
    }

    public function documentQuery(): IDocumentQuery
    {
        // TODO: Implement documentQuery() method.
    }


    public function close(){ }

    /**
     *  Loads the specified entity with the specified id.
     */
    public function load(string $clazz, string $id, ?ArrayCollection $includes=null)
    {
        $loadOperation = new LoadOperation($this);
        $loadOperation->byId($id);
        $command = $loadOperation->createRequest();

        if(null !== $command){
            $this->sessionInfo = new SessionInfo($this,$this->options,$this->documentStore);
            $this->_requestExecutor->execute($command,$this->sessionInfo);
            $loadOperation->setResult($command->getResult());
        }
        return $loadOperation->getDocument($clazz,$id);
    }
    /**
     * !!!!! NO USER DATA FORMATING ( case or anything )--- ONLY SERIALIZE FOR RAVENDB READY
     */
    public function store(object|string $entity, string $id, ?string $changeVector = null, ?string $forceConcurrencyCheck = null)
    {
        return $this->storeInternal($entity,$id,$changeVector);
    }

    public function saveChanges(){
        $saveChangeOperation = new BatchOperation($this);
        try{
            $command = $saveChangeOperation->createRequest();
            $this->noTracking = false;
            if(null === $command) return;
            if($this->noTracking === true) {
                throw new IllegalStateException("Cannot execute saveChanges when entity tracking is disabled in session.");
            }
            $this->_requestExecutor->execute($command,null);
            $saveChangeOperation->setResult($command->getResult());
        } finally {
            $this->close();
        }
    }


    public function delete(string $id, string $expectedChangeVector)
    {
        // TODO: Implement delete() method.
    }


    public function include(string $path): ILoaderWithInclude
    {
        // TODO: Implement include() method.
    }

    public function getConventions(): DocumentConventions
    {
        return $this->_requestExecutor->getConventions();
    }

    public function advanced():IAdvancedSessionOperations
    {
        return $this;
    }

    public function getExternalState():ArrayCollection{
        if(null === $this->externalState){
            $this->externalState = new ArrayCollection();
        }
        return $this->externalState;
    }

    public function hasChanges(object $entity): bool
    {
        foreach($this->documentsByEntity as $entity){
            $document = JsonExtensions::storeSerializer()->serialize([$entity->getKey()=>$entity->getValue()]);
            //dd($document);
        }
    }

    public function getMaxNumberOfRequestsPerSession(): int
    {
        return $this->getMaxNumberOfRequestsPerSession;
    }

    public function setMaxNumberOfRequestsPerSession(int $maxRequests): void
    {
       $this->getMaxNumberOfRequestsPerSession = $maxRequests;
    }

    public function storeIdentifier(): string
    {
        return $this->_documentStore->getIdentifier().";".$this->getDatabaseName();
    }

    public function isUseOptimisticConcurrency(): bool
    {
        return $this->useOptimisticConcurrency;
    }

    public function setUseOptimisticConcurrency(bool $useOptimisticConcurrency):void
    {
        $this->useOptimisticConcurrency = $useOptimisticConcurrency;
    }

    public function clear(): void
    {
        $this->documentsByEntity->clear();
    }

    public function defer(ICommandData $command, ICommandData ...$commands): void
    {
        // TODO: Implement defer() method.
    }

    public function evict($entity): void
    {

    }

    public function getDocumentId(object $entity): ?string
    {
        if(null === $entity) return null;
        $value = $this->documentsByEntity->get($entity);
        return null !== $value ? $value.$this->getId() : null;
    }

    public function getMetadataFor($instance): IMetadataDictionary
    {
        if(null === $instance) throw new \InvalidArgumentException('Instance cannot be null');
        $documentInfo = $this->getDocumentInfo($instance);
    }

    public function getChangeVectorFor(string $instance): string
    {
        if(null === $instance) throw new \InvalidArgumentException('instance cannot be null');
        $documentInfo = $this->getDocumentInfo($instance);
        $changeVector = $documentInfo->getMetadata()->get(Constants::METADATA_CHANGE_VECTOR);
    }

    public function getDocumentInfo(object $instance):DocumentInfo {
        $documentInfo = $this->documentsByEntity->get($instance);
        if(null !== $documentInfo) return $documentInfo;
    }

    public function getCountersFor(string $instance): array
    {
        // TODO: Implement getCountersFor() method.
    }

    public function getTimeSeriesFor($instance): array
    {
        // TODO: Implement getTimeSeriesFor() method.
    }

    public function getLastModifiedFor(string $instance): DateTimeImmutable
    {
        if(null === $instance) throw new \InvalidArgumentException("Instance cannot be null");

        $documentInfo = $this->getDocumentInfo($instance);
        $lastModified = $documentInfo->getMetadata()->get(Constants::METADATA_LAST_MODIFIED);
        if(null !== $lastModified && !is_null($lastModified)){
            // TODO MAP THE VALUE
        }
    }

    public function isLoaded(string $id): bool
    {

    }

    public function isLoadedOrDeleted(string $id){
        $documentInfo = $this->documentsById->getValue($id);
        return ($documentInfo !== null && ($documentInfo));
    }

    public function ignoreChangesFor(object $entity): void
    {
        $this->getDocumentInfo($entity)->setIgnoreChanges(true);
    }

    public function whatChanged(): DocumentsChanges
    {
        $changes = new ArrayCollection();
    }

    public function waitForReplicationAfterSaveChanges(): void
    {
        // TODO: Implement waitForReplicationAfterSaveChanges() method.
    }

    public function getSession(): InMemoryDocumentSessionOperations
    {
        return $this;
    }

    public function exists(string $id): bool
    {
        if(null === $id) throw new \InvalidArgumentException('id cannot be null');
        if($this->_knownMissingIds->containsKey($id)) return false;
        if(null !== $this->_knownMissingIds->get($id)) return true;
    }

    public function loadInternal(array $ids, LoadOperation $operation, ?OutputStream $stream=null){
        $operation->byIds($ids);
        $command = $operation->createRequest();
        if(null !== $command){
            $this->_requestExecutor->execute($command,$this->sessionInfo);
        }else{
            return $operation->setResult($command->getResult());
        }
    }

    /**
     * Determines whether the specified entity has changed.
     * @param entity Entity to check
     * @return true if entity has changed
     */
    public function hasChanged(object $entity): bool {
        return parent::hasChanged($entity);
    }
}
