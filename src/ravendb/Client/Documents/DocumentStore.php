<?php

namespace RavenDB\Client\Documents;

use InvalidArgumentException;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Identity\MultiDatabaseHiLoIdGenerator;
use RavenDB\Client\Documents\Operations\MaintenanceOperationExecutor;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Documents\Smuggler\DatabaseSmuggler;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\EventHandler;
use RavenDB\Client\Util\StringUtils;
use Ramsey\Uuid\Uuid;

/**
 * Class DocumentStore
 * @package RavenDB\Client\Documents
 */
class DocumentStore extends DocumentStoreBase
{
    /*
     private final ConcurrentMap<String, Lazy<RequestExecutor>> requestExecutors = new ConcurrentSkipListMap<>(String.CASE_INSENSITIVE_ORDER);
*/
    private MultiDatabaseHiLoIdGenerator $_multiDbHiLo;
    private MaintenanceOperationExecutor $maintenanceOperationExecutor;
    private OperationExecutor $operationExecutor;
    private DatabaseSmuggler $_smuggler;
    private ?string $identifier;

    public function __construct(string|array $url = null, ?string $database = null)
    {
        $this->setUrls($url);

        if (StringUtils::isString($url)) {
            $this->setUrls([$url]);
        }

        $this->setDatabase($database);
    }

    public function getIdentifier(): ?string
    {
        if (null !== $this->identifier) {
            return $this->identifier;
        }

        if (null === $this->urls) {
            return null;
        }

        if (null !== $this->database) {
            return implode(',', $this->urls) . " (DB: " . $this->database . ")";
        }
        return implode(',', $this->urls);
    }

    public function setIdentifier(?string $identifier = null): ?string
    {
        return $this->identifier = $identifier;
    }

    /*@SuppressWarnings("EmptyTryBlock")
    public void close() {
         EventHelper.invoke(beforeClose, this, EventArgs.EMPTY);
         if (_multiDbHiLo != null) {
             try {
                 _multiDbHiLo.returnUnusedRange();
             } catch (Exception e) {
                 // ignore
             }
         }

         if (subscriptions() != null) {
             subscriptions().close();
         }

         disposed = true;

         EventHelper.invoke(new ArrayList<>(afterClose), this, EventArgs.EMPTY);

         for (Map.Entry<String, Lazy<RequestExecutor>> kvp : requestExecutors.entrySet()) {
             if (!kvp.getValue().isValueCreated()) {
                 continue;
             }

             kvp.getValue().getValue().close();
         }

     }*/

    public function openSession(string|SessionOptions|null $database = null, ?SessionOptions $options = null): IDocumentStore
    {

        if (null !== $database && null === $options) {
            $sessionOptions = new SessionOptions();
            $sessionOptions->setDatabase($database);
        }

        if (null === $database && null !== $options) {
            $this->assertInitialized();
            $this->ensureNotClosed();
            $sessionId = Uuid::uuid4()->toString(); // TODO: uncompleted method
        }
    }

    public function initialize(): IDocumentStore
    {
        if ($this->initialized) {
            return $this;
        }
        $this->assertValidConfiguration();
    }

    protected function assertValidConfiguration(): void
    {
        if (!StringUtils::isNull($this->urls) || StringUtils::isBlank($this->urls)) {
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
    {
        // TODO: Implement getConventions() method.
    }

    public function bulkInsert(string $database): BulkInsertOperation
    {
        // TODO: Implement bulkInsert() method.
    }

    public function getRequestExecutor(?string $databaseName): RequestExecutor
    {
        try {
            $this->assertInitialized();
        } catch (\Exception $e) {
        }

        $databaseName = $this->getEffectiveDatabase($databaseName);
        $executor = $this->getRequestExecutor($databaseName);
        if (null !== $executor) {
            // TODO: in progress
        }
    }

    public function timeSeries(): TimeSeriesOperations // On go
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
            $this->maintenanceOperationExecutor = new MaintenanceOperationExecutor($this);
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
