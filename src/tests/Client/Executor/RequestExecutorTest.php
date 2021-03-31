<?php

namespace RavenDB\Tests\Client\Executor;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Serverwide\DatabaseRecord;
use RavenDB\Client\Serverwide\Operations\CreateDatabaseOperation;
use RavenDB\Client\Serverwide\Operations\GetDatabaseNamesOperation;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\RemoteTestBase;

class RequestExecutorTest extends RemoteTestBase
{
    public function testCanFetchDatabasesNames()
    {
        $conventions = new DocumentConventions();
        $store = $this->getDocumentStore();
        try {
            $executor = RequestExecutor::create($store->getUrls(), $store->getDatabase(), null, $conventions);
            try {
                $databaseNamesOperation = new GetDatabaseNamesOperation(0, 20);
                $command = $databaseNamesOperation->getCommand($conventions);
                $executor->execute($command);
                $dbNames = $command->getResult();
                AssertUtils::assertThat($dbNames)::contains($store->getDatabase());
            } finally {
                $executor->close();
            }
        } finally {
            $store->close();
        }
    }

    public function testCanCreateDatabase(){
        $store = $this->getDocumentStore();
        try {
            $databaseRecord = new DatabaseRecord();
            $databaseRecord->setDatabaseName("dbnew_1");
            $operation = new CreateDatabaseOperation($databaseRecord, 0);
            $result = $store->maintenance()->server()->send($operation);
            dd($result);
            AssertUtils::assertThat($result)::databaseNotFoundError(); // should pass the test if db not found
        } finally {
            $store->close();
        }
    }
}