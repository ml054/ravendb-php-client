<?php

namespace RavenDB\Client\Documents\Operations;

use InvalidArgumentException;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Util\ObjectUtils;
use RavenDB\Client\Util\StringUtils;

class MaintenanceOperationExecutor
{
    private DocumentStore $store;
    private ?string $databaseName=null;
    private ?RequestExecutor $requestExecutor=null;
    private ?ServerOperationExecutor $serverOperationExecutor=null;

    public function __construct(DocumentStore $store, ?string $databaseName=null)
    {
        $this->store = $store;
        $this->databaseName = ObjectUtils::firstNonNull($databaseName, [$this->store->getDatabase()]);
    }

    private function getRequestExecutor(): RequestExecutor
    {
        if ($this->requestExecutor !== null) {
            return $this->requestExecutor;
        }
        $this->requestExecutor = $this->databaseName !== null ? $this->store->getRequestExecutor($this->databaseName) : null;
        return $this->requestExecutor;
    }

    public function server(): ServerOperationExecutor
    {
        if ($this->serverOperationExecutor === null) {
            $this->serverOperationExecutor = new ServerOperationExecutor($this->store, $this->store->getRequestExecutor($this->store->getDatabase()));
        }
        return $this->serverOperationExecutor;
    }

    public function forDatabase(string $databaseName){
        if(StringUtils::equalsIgnoreCase($this->databaseName,$databaseName)){
            return $this;
        }
        return new $this($this->store,$databaseName);
    }

    public function send(IMaintenanceOperation $operation): object|array|string|null
    {
        $this->assertDatabaseNameSet();
        $command = $operation->getCommand($this->getRequestExecutor()->getConventions());
        $this->getRequestExecutor()->execute($command);
        return $command->getResult();
    }

    private function  assertDatabaseNameSet():void {
        if ($this->databaseName == null) {
            throw new InvalidArgumentException("Cannot use maintenance without a database defined, did you forget to call forDatabase?");
        }
    }
}
