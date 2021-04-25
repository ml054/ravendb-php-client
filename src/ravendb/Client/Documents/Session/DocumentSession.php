<?php /** @noinspection ALL */

/** @noinspection PhpUnhandledExceptionInspection */

namespace RavenDB\Client\Documents\Session;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Documents\Batches\ICommandData;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Documents\Linq\IDocumentQuery;
use RavenDB\Client\Documents\Linq\IDocumentQueryGenerator;
use RavenDB\Client\Documents\Session\Loaders\ILoaderWithInclude;
use RavenDB\Client\Documents\Session\Operations\BatchOperation;
use RavenDB\Client\Documents\Session\Operations\LoadOperation;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Util\ObjectMapper;
use RavenDB\Client\Util\StringUtils;

class DocumentSession extends InMemoryDocumentSessionOperations
    implements IDocumentSessionImpl,IAdvancedSessionOperations,IDocumentQueryGenerator
{
    use ObjectMapper;
    private DocumentStore $documentStore;
    private string $id;
    private SessionOptions $options;
    private ArrayCollection $externalState;

    public function __construct(DocumentStore $documentStore, string $id, SessionOptions $options)
    {
        $this->documentStore = $documentStore;
        $this->id = $id;
        $this->options = $options;
        parent::__construct($documentStore,$id,$options);
    }

    public function documentQuery(): IDocumentQuery
    {
        // TODO: Implement documentQuery() method.
    }

    public function saveChanges(){

        $saveChangeOperation = new BatchOperation($this);
      //  dd($saveChangeOperation);
        $command = $saveChangeOperation->createRequest();
        try{
            if(null === $command) return;
            if($this->noTracking){
                throw new IllegalStateException("Cannot execute saveChanges when entity tracking is disabled in session.");
            }
            $this->_requestExecutor->execute($command,null);
        } finally {
            $this->close();
        }
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
            $this->_requestExecutor->execute($command,$this->sessionInfo,$this->documentStore);
        }
       // dd($clazz);
        return $loadOperation->getDocument($clazz,$id);
    }


    public function delete(string $id, string $expectedChangeVector)
    {
        // TODO: Implement delete() method.
    }

    /**
     * !!!!! NO USER DATA FORMATING ( case or anything )--- ONLY SERIALIZE FOR RAVENDB READY
    */
    public function store(object $entity, string $id, string $changeVector,string $forceConcurrencyCheck=null)
    {
        return $this->storeInternal($entity,$id,$changeVector);
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

    public function hasChanges(): bool
    {
        // TODO: Implement hasChanges() method.
    }

    public function getMaxNumberOfRequestsPerSession(): int
    {
        // TODO: Implement getMaxNumberOfRequestsPerSession() method.
    }

    public function setMaxNumberOfRequestsPerSession(int $maxRequests): void
    {
        // TODO: Implement setMaxNumberOfRequestsPerSession() method.
    }

    public function storeIdentifier(): string
    {
        // TODO: Implement storeIdentifier() method.
    }

    public function isUseOptimisticConcurrency(): bool
    {
        // TODO: Implement isUseOptimisticConcurrency() method.
    }

    public function setUseOptimisticConcurrency(bool $useOptimisticConcurrency):void
    {
        // TODO: Implement setUseOptimisticConcurrency() method.
    }

    public function clear(): void
    {
        // TODO: Implement clear() method.
    }

    public function defer(ICommandData $command, ICommandData ...$commands): void
    {
        // TODO: Implement defer() method.
    }

    public function evict($entity): void
    {
        // TODO: Implement evict() method.
    }

    public function getDocumentId(object $entity): string
    {
        // TODO: Implement getDocumentId() method.
    }

    public function getMetadataFor($instance): IMetadataDictionary
    {
        // TODO: Implement getMetadataFor() method.
    }

    public function getChangeVectorFor(string $instance): string
    {
        // TODO: Implement getChangeVectorFor() method.
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
        // TODO: Implement getLastModifiedFor() method.
    }

    public function hasChanged(object $entity): bool
    {
        // TODO: Implement hasChanged() method.
    }

    public function isLoaded(string $id): bool
    {
        // TODO: Implement isLoaded() method.
    }

    public function ignoreChangesFor(object $entity): void
    {
        // TODO: Implement ignoreChangesFor() method.
    }

    public function whatChanged(): DocumentsChanges
    {
        // TODO: Implement whatChanged() method.
    }

    public function waitForReplicationAfterSaveChanges(): void
    {
        // TODO: Implement waitForReplicationAfterSaveChanges() method.
    }

    public function getSession(): InMemoryDocumentSessionOperations
    {
        // TODO: Implement getSession() method.
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
}
