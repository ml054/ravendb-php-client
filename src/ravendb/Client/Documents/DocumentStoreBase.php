<?php

namespace RavenDB\Client\Documents;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Indexes\IAbstractIndexCreationTask;
use RavenDB\Client\Documents\Operations\MaintenanceOperationExecutor;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Session\EventDispatcher\Dispatcher;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Documents\Smuggler\DatabaseSmuggler;
use RavenDB\Client\Documents\TimeSeries\TimeSeriesOperations;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Primitives\VoidArgs;
use RavenDB\Client\Util\StringUtils;

abstract class DocumentStoreBase implements IDocumentStore
{
    use Dispatcher;
    private array $onBeforeStore; // <EventHandler<BeforeStoreEventArgs>>
    private array $onAfterSaveChanges; // <EventHandler<AfterSaveChangesEventArgs>>
    private array $onBeforeDelete; // <EventHandler<BeforeDeleteEventArgs>>
    private array $onBeforeQuery; // <EventHandler<BeforeQueryEventArgs>>
    private array $onSessionCreated; // <EventHandler<SessionCreatedEventArgs>>
    private array $onBeforeConversionToDocument; // <EventHandler<BeforeConversionToDocumentEventArgs>>
    private array $onAfterConversionToDocument; // <EventHandler<AfterConversionToDocumentEventArgs>>
    private array $onBeforeConversionToEntity; // <EventHandler<BeforeConversionToEntityEventArgs>>
    private array $onAfterConversionToEntity; // <EventHandler<AfterConversionToEntityEventArgs>>
    private array $onBeforeRequest ;// <EventHandler<BeforeRequestEventArgs>>
    private array $onSucceedRequest ;// <EventHandler<SucceedRequestEventArgs>>
    private array $onFailedRequest ;// <EventHandler<FailedRequestEventArgs>>
    private array $onTopologyUpdated ;// <EventHandler<TopologyUpdatedEventArgs>>
    protected array|null $urls;
    protected ?string $database;
    protected bool $initialized=false;
    private ?DocumentConventions $conventions=null;


    public abstract function addBeforeCloseListener(VoidArgs $event): void;
    public abstract function removeBeforeCloseListener(VoidArgs $event): void;
    public abstract function addAfterCloseListener(VoidArgs $event): void;
    public abstract function removeAfterCloseListener(VoidArgs $event):void;

    protected bool $disposed;
    public function isDisposed(): bool { return $this->disposed; }

    public function getEffectiveDatabase(string $database): string
    {
        return self::effectiveDatabase($database);
    }

    public static function effectiveDatabase(string $database, ?IDocumentStore $store = null): string
    {
        if (null === $database) {
            $database = $store->getDatabase();
        }
        if (StringUtils::isNotBlank($database)) {
            return $database;
        }
        throw new \InvalidArgumentException("Cannot determine database to operate on. " .
            "Please either specify 'database' directly as an action parameter " .
            "or set the default database to operate on using 'DocumentStore.setDatabase' method. " .
            "Did you forget to pass 'database' parameter? ");
    }
    /**
     * Set the database instance
     * @param string|null $database
     * @return string|null
     */
    public function setDatabase(?string $database = null): ?string
    {
        return $this->database = $database;
    }
    public function close()
    {
        // TODO: Implement close() method.
    }

    public function getIdentifier(): string
    {
        // TODO: Implement getIdentifier() method.
    }

    public function setIdentifier(string $identifier): void
    {
        // TODO: Implement setIdentifier() method.
    }

    public function initialize(): IDocumentStore
    {
        // TODO: Implement initialize() method.
    }

    public function openSession(SessionOptions $sessionOptions): IDocumentStore
    {
        // TODO: Implement openSession() method.
    }

    function executeIndex(IAbstractIndexCreationTask $task, string $database): void
    {
        // TODO: Implement executeIndex() method.
    }

    function executeIndexes(IAbstractIndexCreationTask $tasks): void
    {
        // TODO: Implement executeIndexes() method.
    }

    public function getConventions(): DocumentConventions
    {
        if(null === $this->conventions){
            $this->conventions = new DocumentConventions();
        }
        return $this->conventions;
    }

    public function setConvention(DocumentConventions $conventions){
        try {
            $this->assertNotInitialized('conventions');
        } catch (\Exception $e) {
        }
        $this->conventions = $conventions;
    }
    public function getUrls(): array|string
    {
        return $this->urls;
    }

    public function bulkInsert(string $database): BulkInsertOperation
    {
        // TODO: Implement bulkInsert() method.
    }

    /**
     * @return string|null
     */
    public function getDatabase(): ?string
    {
        return $this->database;
    }

    public function getRequestExecutor(?string $databaseName = null): RequestExecutor
    {
        // TODO: Implement getRequestExecutor() method.
    }

    public function timeSeries(): TimeSeriesOperations
    {
        // TODO: Implement timeSeries() method.
    }

    public function maintenance(): MaintenanceOperationExecutor
    {
        // TODO: Implement maintenance() method.
    }

    public function operations(): OperationExecutor
    {
        // TODO: Implement operations() method.
    }

    public function smuggler(): DatabaseSmuggler
    {
        // TODO: Implement smuggler() method.
    }

    public function setRequestTimeout(int $timeout, ?string $database): Closable
    {
        // TODO: Implement setRequestTimeout() method.
    }

    protected function ensureNotClosed():void {
        if ($this->disposed) {
            throw new IllegalStateException("The document store has already been disposed and cannot be used");
        }
    }

    public function assertInitialized():void {
        if (!$this->initialized) {
            throw new IllegalStateException("You cannot open a session or access the database commands before initializing the document store. Did you forget calling initialize()?");
        }
    }

    private function assertNotInitialized(string $property):void {
        if ($this->initialized) {
            throw new IllegalStateException("You cannot set '".$property."' after the document store has been initialized.");
        }
    }

    /**
     * Format the url values
     * @param array|string $values
     * @return void
     */
    public function setUrls(string|array $values): void
    {
        if (null === $values) throw new \InvalidArgumentException("value cannot be null");

        $collect = $values;

        if (is_array($values)) {

            $collect = [];
            for ($i = 0; $i < count($values); $i++) {

                $values[$i] ?: throw new \InvalidArgumentException("value cannot be null");
                // TODO: check URL to migrate to an Utils (UrlUtils::checkUrl()). based on occurrences
                if (false === filter_var($values[$i], FILTER_VALIDATE_URL)) {
                    throw new \InvalidArgumentException("The url " . $values[$i] . " is not valid");
                }
                // TODO rtrim to StringUtils
                $collect[$i] = rtrim($values[$i], "/");
            }
        }

        $this->urls = $collect;
    }
}
