<?php

namespace RavenDB\Tests\Client;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Serverwide\Operations\GetDatabaseNamesOperation;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\Poc;
use RavenDB\Tests\Client\RemoteTestBase;

class CrudTest extends RemoteTestBase
{
    public function testCrudCanUpdatePropertyFromNullToObject(){
        $store = $this->getDocumentStore();
        try {
            $poc = new Poc();
            $poc->setName("aviv");
            $poc->setObj(null);

        } finally {
            $store->close();
        }
    }
}
