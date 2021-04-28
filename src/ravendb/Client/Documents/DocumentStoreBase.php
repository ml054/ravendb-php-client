<?php

namespace RavenDB\Client\Documents;
use Ds\Map;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Indexes\IAbstractIndexCreationTask;
use RavenDB\Client\Documents\Operations\MaintenanceOperationExecutor;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Session\IDocumentSession;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Documents\Smuggler\DatabaseSmuggler;
use RavenDB\Client\Documents\TimeSeries\TimeSeriesOperations;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\StringUtils;

abstract class DocumentStoreBase implements IDocumentStore
{
    protected array|null $urls;
    protected ?string $database;
    protected bool $initialized=false;
    private ?DocumentConventions $conventions=null;
    /**
     * @psalm-return Map<string, string>
    */
    private Map $_lastRaftIndexPerDatabase;

    protected ?bool $disposed=null;
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
    public function close() { }

    public function getIdentifier(): string { }

    public function setIdentifier(string $identifier): void { }

    public function initialize(): IDocumentStore{ }

    public function openSession(SessionOptions $sessionOptions): IDocumentSession
    {
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

    public function bulkInsert(string $database): BulkInsertOperation{ }
    /**
     * @return string|null
     */
    public function getDatabase(): ?string
    {
        return $this->database;
    }
    public function getRequestExecutor(?string $databaseName = null): RequestExecutor{}
    public function timeSeries(): TimeSeriesOperations{}
    public function maintenance(): MaintenanceOperationExecutor {}
    public function operations(): OperationExecutor{ }
    public function smuggler(): DatabaseSmuggler{ }
    public function setRequestTimeout(int $timeout, ?string $database): Closable{}

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
                if (false === filter_var($values[$i], FILTER_VALIDATE_URL)) {
                    throw new \InvalidArgumentException("The url " . $values[$i] . " is not valid");
                }
                $collect[$i] = rtrim($values[$i], "/");
            }
        }
        $this->urls = $collect;
    }

    /**
     * @return array
     */
    public function getLastRaftIndexPerDatabase(): array
    {
        return $this->_lastRaftIndexPerDatabase;
    }

    /**
     * @param array $lastRaftIndexPerDatabase
     */
    public function setLastRaftIndexPerDatabase(array $lastRaftIndexPerDatabase): void
    {

        $this->_lastRaftIndexPerDatabase = $lastRaftIndexPerDatabase;
    }

}
