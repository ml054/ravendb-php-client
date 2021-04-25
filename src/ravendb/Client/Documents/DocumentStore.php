<?php

namespace RavenDB\Client\Documents;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use RavenDB\Client\Documents\BulkInsertOperation;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Identity\MultiDatabaseHiLoIdGenerator;
use RavenDB\Client\Documents\IDocumentStore;
use RavenDB\Client\Documents\Indexes\IAbstractIndexCreationTask;
use RavenDB\Client\Documents\Operations\MaintenanceOperationExecutor;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Session\DocumentSession;
use RavenDB\Client\Documents\Session\IDocumentSession;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Documents\Smuggler\DatabaseSmuggler;
use RavenDB\Client\Documents\TimeSeries\TimeSeriesOperations;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Primitives\VoidArgs;
use RavenDB\Client\Util\StringUtils;

class DocumentStore extends DocumentStoreBase
{
    private ?MaintenanceOperationExecutor $maintenanceOperationExecutor = null;
    private OperationExecutor $operationExecutor;
    private DatabaseSmuggler $_smuggler;
    private MultiDatabaseHiLoIdGenerator $_multiDbHiLo;
    private ?string $identifier;

    public function __construct(string|array $url, ?string $database = null)
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
        if (null !== $this->identifier) { return $this->identifier;}
        if (null === $this->urls) { return false;}
        if (null !== $this->database) { return implode(',', $this->urls) . " (DB: " . $this->database . ")";}
        return implode(',', $this->urls);
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    protected function assertValidConfiguration(){
        if(null === $this->urls || count($this->urls) ===0 ) throw new \InvalidArgumentException("Document store URLs cannot be empty");
    }

    /**
     * @throws \Exception
     */
    public function initialize(): IDocumentStore
    {
        if($this->initialized){
            return $this;
        }
        $this->assertValidConfiguration();
        RequestExecutor::validateUrls($this->urls);
        try {
            if(null === $this->getConventions()->getDocumentIdGenerator()){ // don't overwrite what the user is doing
                $generator = new MultiDatabaseHiLoIdGenerator($this);
                $this->_multiDbHiLo = $generator;
            }
            $this->getConventions()->freeze();
            $this->initialized = true;
        } catch(\Exception $e){
            $this->close();
            throw new \Exception($e);
        }
        return $this;
    }

    public function getRequestExecutor(?string $database=null): RequestExecutor
    {
        return $this->internalGetRequestExecutor($database);
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
        } catch (\Exception $e) {
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

    public function openSession(SessionOptions $sessionOptions): IDocumentSession
    {
        $this->assertInitialized();
        $this->ensureNotClosed();
        $sessionID = Uuid::uuid4()->toString();
        return new DocumentSession($this,$sessionID,$sessionOptions);
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

    public function addBeforeCloseListener(VoidArgs $event): void
    {
        // TODO: Implement addBeforeCloseListener() method.
    }

    public function removeBeforeCloseListener(VoidArgs $event): void
    {
        // TODO: Implement removeBeforeCloseListener() method.
    }

    public function addAfterCloseListener(VoidArgs $event): void
    {
        // TODO: Implement addAfterCloseListener() method.
    }

    public function removeAfterCloseListener(VoidArgs $event)
    {
        // TODO: Implement removeAfterCloseListener() method.
    }
}
