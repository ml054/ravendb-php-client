<?php

namespace RavenDB\Tests\Client\Executor;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Serverwide\DatabaseRecord;
use RavenDB\Client\Serverwide\Operations\CreateDatabaseOperation;
use RavenDB\Client\Serverwide\Operations\DeleteDatabasesOperation;
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

    public function testCanIssueManyRequests()
    {
        $conventions = new DocumentConventions();
        $store = $this->getDocumentStore();
        try {
            $executor = RequestExecutor::create($store->getUrls(), $store->getDatabase(), null, $conventions);
            try {
                        for( $i=0; $i < 50; $i++ ){
                            $databaseNamesOperation = new GetDatabaseNamesOperation(0, 20);
                            $command = $databaseNamesOperation->getCommand($conventions);
                            $executor->execute($command);
                        }
                        AssertUtils::assertThat($i)::isEqualTo(50);
            } finally {
                $executor->close();
            }
        } finally {
            $store->close();
        }
    }
    /**
     * @throws \Exception
     */
    public function testCanCreateDatabase(){
        $store = $this->getDocumentStoreMaintenance();
        try {
                $store->maintenance()->server()->send(new CreateDatabaseOperation(new DatabaseRecord($store->getDatabase()), 1));
        } finally {
            $store->close();
        }
    }

    /**
     * @throws \Exception
     */
    public function testCanCreateDatabaseIssueManyRequests(){
        $store = $this->getDocumentStoreMaintenance();
        try {
            for($i=0;$i<5;$i++){
                $store->maintenance()->server()->send(new CreateDatabaseOperation(new DatabaseRecord($store->getDatabase()."_ref_".$i), 1));
            }
        } finally {
            $store->close();
        }
        AssertUtils::assertThat($i)::isEqualTo(5);
    }

    public function testCanDeleteDatabase(){
        $store = $this->getDocumentStore();
        try {
            $store->maintenance()->server()->send(new DeleteDatabasesOperation("db3", true,null,null));
        } finally {
            $store->close();
        }
    }
}