<?php

namespace RavenDB\Client\Documents;

use InvalidArgumentException;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Identity\MultiDatabaseHiLoIdGenerator;
use RavenDB\Client\Documents\Indexes\IAbstractIndexCreationTask;
use RavenDB\Client\Documents\Operations\MaintenanceOperationExecutor;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Session\DocumentSession;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Documents\Smuggler\DatabaseSmuggler;
use RavenDB\Client\Documents\TimeSeries\TimeSeriesOperations;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\EventHandler;
use RavenDB\Client\Util\StringUtils;
use Ramsey\Uuid\Uuid;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class DocumentStore extends DocumentStoreBase
{
    private MultiDatabaseHiLoIdGenerator $_multiDbHiLo;
    private ?MaintenanceOperationExecutor $maintenanceOperationExecutor = null;
    private OperationExecutor $operationExecutor;
    private DatabaseSmuggler $_smuggler;
    private ?string $identifier;

    public function __construct(string|array $url = null, ?string $database = null)
    {
        if (StringUtils::isString($url)) {
            $this->setUrls([$url]);
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

        /*
         * TODO:
        RequestExecutor.validateUrls(urls, getCertificate());

        try {
            if (getConventions().getDocumentIdGenerator() == null) { // don't overwrite what the user is doing
                MultiDatabaseHiLoIdGenerator generator = new MultiDatabaseHiLoIdGenerator(this);
                _multiDbHiLo = generator;

                getConventions().setDocumentIdGenerator(generator::generateDocumentId);
            }

            getConventions().freeze();
            initialized = true;
        } catch (Exception e) {
            close();
            throw ExceptionsUtils.unwrapException(e);
        }
         * */
        return $this;
    }

    protected function assertValidConfiguration(): void
    {
        if (StringUtils::isNull($this->urls) || StringUtils::isBlank($this->urls)) {
            throw new InvalidArgumentException("Document store URLs cannot be empty");
        }
    }

    public function addAfterCloseListener(EventHandler $event): void
    {
        // TODO: Implement addAfterCloseListener() method.
    }

    public function removeAfterCloseListener(EventHandler $event)
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

    public function getConventions(): DocumentConventions
    {   /*TODO: JUST FOR TESTING*/
        return new DocumentConventions();
    }

    public function bulkInsert(string $database): BulkInsertOperation
    {
        // TODO: Implement bulkInsert() method.
    }

    /*
     *  Supplier<RequestExecutor> createRequestExecutor = () -> {
        RequestExecutor requestExecutor = RequestExecutor.create(getUrls(), effectiveDatabase, getCertificate(), getCertificatePrivateKeyPassword(), getTrustStore(), executorService, getConventions());
        registerEvents(requestExecutor);

        return requestExecutor;*/
    public function getRequestExecutor(?string $database): RequestExecutor
    {
        $database = $this->internalGetRequestExecutor($database);
        return $database;
        //dd($database);
    }

    private function internalGetRequestExecutor($databaseName):RequestExecutor
    {
            $conventions = new DocumentConventions();
            return RequestExecutor::create(null,$databaseName,null,$conventions);
    }

    public function timeSeries(): TimeSeriesOperations
    {
        // TODO: Implement timeSeries() method. Check with Marcin. Imported from Interface
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

    public function smuggler(): DatabaseSmuggler
    {
        if (null === $this->_smuggler) {
            $this->_smuggler = new DatabaseSmuggler($this);
        }
        return $this->_smuggler;
    }

    public function setRequestTimeout(int $timeout, ?string $database = null): Closable
    {
        return $this->setRequestTimeout($timeout, null);
    }
}

/*

  */