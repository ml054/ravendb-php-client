<?php
namespace RavenDB\Tests\Client\Sessions;

use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeRequestEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeStoreEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\InMemoryDocSessionDispatcher;
use RavenDB\Client\Documents\Session\EventDispatcher\InMemoryDocSessionSubscriber;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Client\Util\EventNameHolder;
use RavenDB\Tests\Client\RemoteTestBase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Contracts\EventDispatcher\Event;

class InMemorySessionTest extends RemoteTestBase
{
    use EventNameHolder;
    public function testCanTriggerListener(){
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

    public function testCanInMemorySessionAddBeforeStoreListener(){
        $instance = new InMemoryBase();
        $handler = new BeforeStoreEventArgs(null,'454545',new \stdClass());
        $instance->addBeforeStoreListener($handler);
    }

    public function testCanInMemorySessionRemoveBeforeStoreListener(){
        $instance = new InMemoryBase();
        $handler = new BeforeStoreEventArgs(null,'454545',new \stdClass());
        $instance->removeBeforeStoreListener($handler);
    }
    // WILL SIMULATE A SEQUENCE - AN EVENT CAN MANAGE MULTIPLE LISTENERS
    public function testCanRunEventsSequence(){
        $handler  = new BeforeRequestEventArgs('database_name','url',55);
        $dispatcher = new EventDispatcher();
        $subscriber = new InMemoryDocSessionSubscriber();
        $event = new InMemoryDocSessionDispatcher($handler);
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, 'onSequenceBeforeStore');
    }
    // The goal track an object during its changes event
    public function testTraceableDispatcher(){

    }
}
