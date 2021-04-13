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
                        for($i=0;$i < 50; $i++){
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
    /**
     * @throws \Exception
     * TODO : THE DELETATION FLOW HITS THE SERVER BUT RESPONSE IS SIMILAR TO RavenDB-10942 BACK IN THE V.4. TO CHECK WITH TECH
     * PREVIOUS REFERENCE : https://issues.hibernatingrhinos.com/issue/RavenDB-10942
     * PREVIOUS FIX : https://github.com/ravendb/ravendb/pull/5936/commits/41f917e8481bb575ec34dada76f4ae69ad276769
     * ================= Below traces from my local ravendb server when running the testCanDeleteDatabase test (500 Internal Server Error)
     * {
        "Url": "/admin/databases",
        "Type": "System.FormatException",
        "Message": "Could not convert Sparrow.Json.LazyStringValue ('db3') to Sparrow.Json.BlittableJsonReaderArray",
        "Error": "System.FormatException: Could not convert Sparrow.Json.LazyStringValue ('db3') to Sparrow.Json.BlittableJsonReaderArray\n --->
         System.InvalidCastException: Invalid cast from 'System.String' to 'Sparrow.Json.BlittableJsonReaderArray'.\n
         at System.Convert.DefaultToType(IConvertible value, Type targetType, IFormatProvider provider)\n
         at System.String.System.IConvertible.ToType(Type type, IFormatProvider provider)\n
         at Sparrow.Json.BlittableJsonReaderObject.ConvertType[T](Object result, T& obj)
         in C:\\Builds\\RavenDB-Stable-5.1\\51016\\src\\Sparrow\\Json\\BlittableJsonReaderObject.cs:line 519\
    }
     */
    public function testCanDeleteDatabase(){
        $store = $this->getDocumentStore();
        try {
            $store->maintenance()->server()->send(new DeleteDatabasesOperation("db3", true,null,null));
        } finally {
            $store->close();
        }
    }
}