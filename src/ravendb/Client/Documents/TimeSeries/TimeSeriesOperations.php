<?php


namespace RavenDB\Client\Documents\TimeSeries;


use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Documents\IDocumentStore;
use RavenDB\Client\Documents\Operations\MaintenanceOperationExecutor;

class TimeSeriesOperations
{
    private IDocumentStore $_store;
    private string $_database;
    private MaintenanceOperationExecutor $_executor;

    public function __construct(DocumentStore $store, string $database)
    {
        $this->_store = $store;
        $this->_database = $database;
    }


}