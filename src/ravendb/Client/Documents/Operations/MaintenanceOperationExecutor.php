<?php

namespace RavenDB\Client\Documents\Operations;

use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Http\ClusterRequestExecutor;
use RavenDB\Client\Http\RequestExecutor;

class MaintenanceOperationExecutor
{
    private DocumentStore $store;
    private ?string $databaseName=null;
    private ?ClusterRequestExecutor $requestExecutor=null;
    private ?ServerOperationExecutor $serverOperationExecutor=null;

    public function __construct(DocumentStore $store, ?string $databaseName=null)
    {
        $this->store = $store;
        /*
         * TODO : this.databaseName = ObjectUtils.firstNonNull(databaseName, store.getDatabase());
         * */
        $this->databaseName = $this->store->getDatabase();
    }

    public function server(): ServerOperationExecutor
    {
        if ($this->serverOperationExecutor !== null) {
            return $this->serverOperationExecutor;
        } else {
            // TODO: CHECK WITH MARCIN FOR THE EXECUTOR BIND TO THE STORE
            $this->serverOperationExecutor = new ServerOperationExecutor($this->store,$this->store->getRequestExecutor($this->store->getDatabase()));
            return $this->serverOperationExecutor;
        }
    }

    private function getRequestExecutor(): RequestExecutor
    {
        if ($this->requestExecutor !== null) {
            return $this->requestExecutor;
        }
        $this->requestExecutor = $this->databaseName !== null ? $this->store->getRequestExecutor($this->databaseName) : null;
        return $this->requestExecutor;
    }
}
/*
public class MaintenanceOperationExecutor {


    public MaintenanceOperationExecutor forDatabase(String databaseName) {
        if (StringUtils.equalsIgnoreCase(this.databaseName, databaseName)) {
            return this;
        }

        return new MaintenanceOperationExecutor(store, databaseName);
    }

    public Operation sendAsync(IMaintenanceOperation<OperationIdResult> operation) {
        assertDatabaseNameSet();
        RavenCommand<OperationIdResult> command = operation.getCommand(getRequestExecutor().getConventions());

        getRequestExecutor().execute(command);
        return new Operation(getRequestExecutor(),
                () -> store.changes(), getRequestExecutor().getConventions(),
                command.getResult().getOperationId(),
                ObjectUtils.firstNonNull(command.getSelectedNodeTag(), command.getResult().getOperationNodeTag()));
    }
}
 * */