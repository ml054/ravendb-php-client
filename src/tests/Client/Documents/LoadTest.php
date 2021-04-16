<?php

namespace RavenDB\Tests\Client\Documents;

use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Tests\Client\CrudEntities\Foo;
use RavenDB\Tests\Client\RemoteTestBase;

class LoadTest extends RemoteTestBase
{
    public function testLoadWithIncludes(){
            try{
                $store = $this->getDocumentStore();
                $options = new SessionOptions();
                $options->setDatabase('new_db_1');
                try {
                   $session = $store->openSession($options);
                   $foo = (new Foo())->setName("Beginning");
                   $session->store($foo);
                   // TODO
                   $session->saveChanges();
                } finally {
                    $store->close();
                }
            } finally {
                $store->close();
            }
        }
}
