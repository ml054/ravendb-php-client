<?php

namespace RavenDB\Client\Documents;

use RavenDB\Client\Documents\Identity\MultiDatabaseHiLoIdGenerator;
use RavenDB\Client\Documents\Operations\MaintenanceOperationExecutor;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Documents\Smuggler\DatabaseSmuggler;
use RavenDB\Client\Util\StringUtils;
use Ramsey\Uuid\Uuid;

/**
 * Class DocumentStore
 * @package RavenDB\Client\Documents
 */
class DocumentStore extends DocumentStoreBase
{
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

    public function openSession(?string $database = null, ?SessionOptions $options = null)
    {

        if (null !== $database && null === $options) {
            $sessionOptions = new SessionOptions();
            $sessionOptions->setDatabase($database);
        }

        if (null === $database && null !== $options) {
            $this->assertInitialized();
            $this->ensureNotClosed();

            $sessionId = Uuid::uuid4()->toString();
            //  $session = new DocumentSe

        }
    }
}
