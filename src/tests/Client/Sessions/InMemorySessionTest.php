<?php

namespace RavenDB\Tests\Client\Sessions;

use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeRequestEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\InMemoryDocSessionDispatcher;
use RavenDB\Client\Documents\Session\EventDispatcher\InMemoryDocSessionSubscriber;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\RemoteTestBase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Contracts\EventDispatcher\Event;

class InMemorySessionTest extends RemoteTestBase
{
    public function testCanOnBeforeConversionToDocumentAddingListener(){
        $handler  = new BeforeRequestEventArgs('database_name','url',55);
        $dispatcher = new EventDispatcher();
        $subscriber = new InMemoryDocSessionSubscriber();
        $event = new InMemoryDocSessionDispatcher($handler);
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, 'onBeforeStore.add');
        AssertUtils::assertThat($event)::isNotEmpty();
        AssertUtils::assertThat($event)::isObject();
        AssertUtils::assertThat($event)::isInstanceOf(Event::class);
        AssertUtils::assertThat($event->getObject())::isInstanceOf(BeforeRequestEventArgs::class);
        AssertUtils::assertThat($event->getObject()->getUrl())::isNotNull();
        AssertUtils::assertThat($event->getObject()->getAttemptNumber())::isEqualTo(55);
    }

}
