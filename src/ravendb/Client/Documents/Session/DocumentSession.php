<?php /** @noinspection ALL */

/** @noinspection PhpUnhandledExceptionInspection */

namespace RavenDB\Client\Documents\Session;
use DateTimeImmutable;
use RavenDB\Client\Documents\Batches\ICommandData;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Documents\Session\Loaders\ILoaderWithInclude;
use RavenDB\Client\Documents\Session\Operations\BatchOperation;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Util\StringUtils;

class DocumentSession extends InMemoryDocumentSessionOperations implements IDocumentSessionImpl,IAdvancedSessionOperations
{
    private DocumentStore $documentStore;
    private string $id;
    private SessionOptions $options;
    private array $externalState;

    public function __construct(DocumentStore $documentStore, string $id, SessionOptions $options)
    {
        $this->documentStore = $documentStore;
        $this->id = $id;
        $this->options = $options;
        parent::__construct($documentStore,$id,$options);
    }
    public function saveChanges(){
        $saveChangeOperation = new BatchOperation($this);
        $command = $saveChangeOperation->createRequest();
        try{
            if(null === $command) return;
            if($this->noTracking){
                throw new IllegalStateException("Cannot execute saveChanges when entity tracking is disabled in session.");
            }
            $this->_requestExecutor->execute($command,$this->sessionInfo);
        } finally {
            $this->close();
        }
    }

    public function close(){ }

    /**
     *  Loads the specified entity with the specified id.
     */
    public function load(string $clazz, string $id)
    {

    }

    public function delete(string $id, string $expectedChangeVector)
    {
        // TODO: Implement delete() method.
    }

    public function store(object $entity, string $id, string $changeVector=null): void
    {
        $serializer = JsonExtensions::serializer();
        $json = $serializer->normalize($entity);
        $pascalizer = StringUtils::pascalize($json);
        $encode = $serializer->encode($pascalizer,'json');
        dd($encode);
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

    public function getExternalState(){
        if(null === $this->externalState){
            $this->externalState = [];
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

    public function setUseOptimisticConcurrency(bool $useOptimisticConcurrency)
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
}
