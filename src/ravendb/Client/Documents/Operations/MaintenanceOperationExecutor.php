<?php

namespace RavenDB\Client\Documents\Operations;

use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Util\ObjectUtils;
use RavenDB\Client\Util\StringUtils;

class MaintenanceOperationExecutor
{
    private DocumentStore $store;
    private string $databaseName;
    private RequestExecutor $requestExecutor;
    private ServerOperationExecutor $serverOperationExecutor;

    public function __construct(DocumentStore $store, string $databaseName)
    {
        $this->store = $store;
        $this->databaseName = ObjectUtils::firstNonNull($databaseName,$store->getDatabase());
    }

    public function MaintenanceOperationExecutor(DocumentStore $store)
    {
        //TODO: $this(store, null);
    }

    private function getRequestExecutor(): RequestExecutor
    {
        if ($this->requestExecutor !== null) {
            return $this->requestExecutor;
        }

     //   $this->requestExecutor = null !== $this->databaseName ? $this->store->get
        return $this->requestExecutor;
    }

    public function server(): ServerOperationExecutor
    {
        if ($this->serverOperationExecutor !== null) {
            return $this->serverOperationExecutor;
        } else {
            $this->serverOperationExecutor = new ServerOperationExecutor();
            return $this->serverOperationExecutor;
        }
    }

    public function forDatabase(string $databaseName): MaintenanceOperationExecutor
    {
        if (StringUtils::equalsIgnoreCase($this->databaseName, $databaseName)) {
            return $this;
        }
        return new MaintenanceOperationExecutor($this->store, $this->databaseName);
    }

    /**
     * @throws IllegalStateException
    */
    private function assertDatabaseNameSet(): void
    {
        if ($this->databaseName === null) {
            throw new IllegalStateException("Cannot use maintenance without a database defined, did you forget to call forDatabase?");
        }
    }
}
