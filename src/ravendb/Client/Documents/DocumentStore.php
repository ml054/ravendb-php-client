<?php

namespace RavenDB\Client\Documents;

use Exception;
use InvalidArgumentException;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Identity\MultiDatabaseHiLoIdGenerator;
use RavenDB\Client\Documents\Indexes\IAbstractIndexCreationTask;
use RavenDB\Client\Documents\Operations\MaintenanceOperationExecutor;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Documents\Smuggler\DatabaseSmuggler;
use RavenDB\Client\Documents\TimeSeries\TimeSeriesOperations;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Util\StringUtils;
use Ramsey\Uuid\Uuid;
use RuntimeException;

// MH : openSession | initialize | maintenaince
class DocumentStore extends DocumentStoreBase
{
    private ?MaintenanceOperationExecutor $maintenanceOperationExecutor = null;
    private OperationExecutor $operationExecutor;
    private DatabaseSmuggler $_smuggler;
    private MultiDatabaseHiLoIdGenerator $_multiDbHiLo;
    private ?string $identifier;

    public function __construct(string|array $url = null, ?string $database = null)
    {
        if (StringUtils::isString($url)) {
            $this->setUrls([$url]);
        }else{
            $this->setUrls($url);
        }

        $this->setDatabase($database);
    }

    public function getIdentifier(): string
    {
        if (null !== $this->identifier) {
            return $this->identifier;
        }

        if (null === $this->urls) {
            return false;
        }

        if (null !== $this->database) {
            return implode(',', $this->urls) . " (DB: " . $this->database . ")";
        }
        return implode(',', $this->urls);
    }

    public function setIdentifier(?string $identifier = null): void
    {
        $this->identifier = $identifier;
    }

    public function openSession(string|SessionOptions $database = null, ?SessionOptions $options = null): IDocumentStore
    {

        if (null !== $database && null === $options) {
            $sessionOptions = new SessionOptions();
            $sessionOptions->setDatabase($database);
        }

        if (null === $database && null !== $options) {
            $this->assertInitialized();
            $this->ensureNotClosed();
            $sessionId = Uuid::uuid4()->toString(); // TODO: uncompleted method
            //TODO: $newSession = new DocumentSession($this, '');
        }
    }

    public function initialize(): IDocumentStore
    {
        if ($this->initialized) {
            return $this;
        }
        $this->assertValidConfiguration();
        RequestExecutor::validateUrls($this->urls,null);
        try{
            if(null === $this->getConventions()->getDocumentIdGenerator()){ // don't overwrite what the user is doing
                $generator = new MultiDatabaseHiLoIdGenerator($this);
                $this->_multiDbHiLo = $generator;
                $this->getConventions()->getDocumentIdGenerator();
            }
            $this->getConventions()->freeze();
            $this->initialized = true;
        }catch (Exception $e){
            $this->close();
            throw new RuntimeException();
        }
        return $this;
    }

    protected function assertValidConfiguration(): void
    {
        if (StringUtils::isNull($this->urls) || StringUtils::isBlank($this->urls)) {
            throw new InvalidArgumentException("Document store URLs cannot be empty");
        }
    }

    public function getConventions(): DocumentConventions
    {   /*TODO: JUST FOR TESTING*/
        return new DocumentConventions();
    }


    public function getRequestExecutor(?string $database=null): RequestExecutor
    {
        $database = $this->internalGetRequestExecutor($database);
        return $database;
    }

    private function internalGetRequestExecutor($databaseName):RequestExecutor
    {
            $conventions = new DocumentConventions();
            return RequestExecutor::create(null,$databaseName,null,$conventions);
    }

    public function maintenance(): MaintenanceOperationExecutor
    {
        try {
            $this->assertInitialized();
        } catch (Exception $e) {
        }
        if (null === $this->maintenanceOperationExecutor) {
            $this->maintenanceOperationExecutor = new MaintenanceOperationExecutor($this, $this->getDatabase());
        }
        return $this->maintenanceOperationExecutor;
    }

    public function operations(): OperationExecutor
    {
        if (null === $this->operationExecutor) {
            $this->operationExecutor = new OperationExecutor($this);
        }
        return $this->operationExecutor;
    }

    public function addAfterCloseListener(EventHandler|\RavenDB\Client\Util\EventHandler $event): void
    {
        // TODO: Implement addAfterCloseListener() method.
    }

    public function removeAfterCloseListener(EventHandler|\RavenDB\Client\Util\EventHandler $event)
    {
        // TODO: Implement removeAfterCloseListener() method.
    }

    function executeIndex(IAbstractIndexCreationTask $task, string $database): void
    {
        // TODO: Implement executeIndex() method.
    }

    function executeIndexes(IAbstractIndexCreationTask $tasks): void
    {
        // TODO: Implement executeIndexes() method.
    }

    public function bulkInsert(string $database): BulkInsertOperation
    {
        // TODO: Implement bulkInsert() method.
    }

    public function timeSeries(): TimeSeriesOperations
    {
        // TODO: Implement timeSeries() method.
    }

    public function smuggler(): DatabaseSmuggler
    {
        // TODO: Implement smuggler() method.
    }

    public function setRequestTimeout(int $timeout, ?string $database): Closable
    {
        // TODO: Implement setRequestTimeout() method.
    }
}
