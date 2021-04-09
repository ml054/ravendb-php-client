<?php

namespace RavenDB\Tests\Client\Sessions;

use RavenDB\Client\Documents\Session\BeforeStoreEventArgs;
use RavenDB\Tests\Client\RemoteTestBase;
use RavenDB\Client\Documents\Session\SessionDispatcher;
use Symfony\Contracts\EventDispatcher\Event;

class SessionDispatcherTest extends RemoteTestBase
{
    public function testCanBeforeStoreEventArgs(){
        $object = new SessionObject();
        $object->setName("TestName");
        $event = new BeforeStoreEventArgs($object);
        $dispatcher = SessionDispatcher::dispatcher();
        $dispatcher->dispatch($event,BeforeStoreEventArgs::NAME);
        $dispatcher->addListener(BeforeStoreEventArgs::NAME, function (Event $event) {
            dd("heredfsdf");
        });
        dd("heredd");
    }
}
