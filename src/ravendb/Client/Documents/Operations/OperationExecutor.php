<?php


namespace RavenDB\Client\Documents\Operations;


use RavenDB\Client\Documents\DocumentStoreBase;
use RavenDB\Client\Documents\IDocumentStore;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Util\StringUtils;

class OperationExecutor
{
    private IDocumentStore $store;
    private string $databaseName;
    private RequestExecutor $requestExecutor;

    public function __construct(DocumentStoreBase|IDocumentStore $store, ?string $databaseName=null)
    {
        $this->store = $store;
        $this->databaseName = $databaseName !== null ? $this->databaseName : $store->getDatabase();

        if (StringUtils::isNotBlank($this->databaseName)) {
            $this->requestExecutor = $this->store->getRequestExecutor($this->databaseName);
        } else {
            throw new IllegalStateException('Cannot use operations without a database defined, did you forget to call forDatabase?');
        }
    }

    public function forDatabase(string $databaseName): OperationExecutor
    {
        if (StringUtils::equalsIgnoreCase($this->databaseName, $databaseName)) {
            return $this;
        }
        return new OperationExecutor($this->store, $databaseName);
    }
}
