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
     *
     */
    public function testCrudCanUpdatePropertyFromNullToObject(){
        $store = $this->getDocumentStore();
        try {
            $option = new SessionOptions();
            $option->setDatabase('db1');
            $session = $store->openSession($option);
            $poc = new Poc();
            $poc->setName("aviv");
            $poc->setObj(null);
            dd($session);
        } catch (\Exception $e) {
            dd($e->getMessage());
        } finally {
            $store->close();
        }
    }
}
