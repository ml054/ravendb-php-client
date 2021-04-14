<?php

namespace RavenDB\Tests\Client;
use RavenDB\Client\Data\Driver\RavenDB;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Client\Util\ObjectMapper;
use RavenDB\Client\Util\StringUtils;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class CrudTest extends RemoteTestBase
{
    /**
     * @throws \Exception
     * TODO : IMPLEMENTATION OF SAVE CHANGE. OPENSESSION OK
     */
    public function testCrudCanUpdatePropertyFromNullToObject(){
        $store = $this->getDocumentStore();
        $options = new SessionOptions();
        $options->setDatabase('new_db_1');
        try {
            $session = $store->openSession($options);
            $poc = new Poc();
            $poc->setName("aviv");
            $poc->setObj(null);
            $session->store($poc,'testeste',"users/1");
            $session->saveChanges();
        } catch (\Exception $e) {
            dd($e->getMessage());
        } finally {
            $store->close();
        }
    }
}