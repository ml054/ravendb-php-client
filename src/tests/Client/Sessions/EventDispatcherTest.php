<?php

namespace RavenDB\Tests\Client\Sessions;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeRequestEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeStoreEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\FailedRequestEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\SucceedRequestEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\TopologyUpdatedEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventHandlerDispatcher;
use RavenDB\Client\Documents\Session\EventDispatcher\EventHandlerSubscriber;
use RavenDB\Client\Documents\Session\EventDispatcher\IEventHandler;
use RavenDB\Client\Documents\Session\EventDispatcher\InMemoryDocSessionDispatcher;
use RavenDB\Client\Documents\Session\EventDispatcher\InMemoryDocSessionSubscriber;
use RavenDB\Client\Http\Topology;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\RemoteTestBase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Contracts\EventDispatcher\Event;

class EventDispatcherTest extends RemoteTestBase
{
    public function testCanRequestEventArgs(){
        $RequestEventArgs = new FailedRequestEventArgs('database_name','http://localhost',new \Exception('something wrong'));
        $dispatcher = new EventDispatcher();
        $subscriber = new EventHandlerSubscriber();
        $event = new EventHandlerDispatcher($RequestEventArgs);
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, EventHandlerDispatcher::NAME);
        AssertUtils::assertThat($event->getObject())::isNotNull();
        AssertUtils::assertThat($event->getObject())::isInstanceOf(FailedRequestEventArgs::class);
        AssertUtils::assertThat($event->getObject()->getDatabase())::isNotEmpty();
        AssertUtils::assertThat($event->getObject()->getException())::isNotEmpty();
        AssertUtils::assertThat($event->getObject()->getUrl())::isNotEmpty();
        AssertUtils::assertThat($event->getObject()->getUrl())::isString();
        AssertUtils::assertThat($event->getObject()->getException())::isInstanceOf(\Exception::class);
    }

    public function testCanSucceedRequestEventArgs(){
        $RequestEventArgs = new SucceedRequestEventArgs('database_name','url',5);
        $dispatcher = new EventDispatcher();

        $subscriber = new EventHandlerSubscriber();
        $event = new EventHandlerDispatcher($RequestEventArgs);

        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, EventHandlerDispatcher::NAME);
        AssertUtils::assertThat($event->getObject())::isNotNull();
        AssertUtils::assertThat($event->getObject()->getAttemptNumber())::isNotNull();
        AssertUtils::assertThat($event->getObject()->getAttemptNumber())::isEqualTo(5);
        AssertUtils::assertThat($event->getObject())::isInstanceOf(SucceedRequestEventArgs::class);
        AssertUtils::assertThat($event->getObject())::isInstanceOf(IEventHandler::class);
    }

    public function testCanBeforeRequestEventArgs(){
        $RequestEventArgs = new BeforeRequestEventArgs('database_name','url',5);
        $dispatcher = new EventDispatcher();
        $subscriber = new EventHandlerSubscriber();
        $event = new EventHandlerDispatcher($RequestEventArgs);
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, EventHandlerDispatcher::NAME);
        AssertUtils::assertThat($event->getObject())::isNotNull();
        AssertUtils::assertThat($event->getObject()->getAttemptNumber())::isNotNull();
        AssertUtils::assertThat($event->getObject()->getAttemptNumber())::isEqualTo(5);
        AssertUtils::assertThat($event->getObject())::isInstanceOf(BeforeRequestEventArgs::class);
        AssertUtils::assertThat($event->getObject())::isInstanceOf(IEventHandler::class);
    }

    public function testCanTopologyUpdatedEventArgs(){
        $topology = new Topology();
        $topology->setEtag('Et57_updating');
        $RequestEventArgs = new TopologyUpdatedEventArgs($topology);
        $dispatcher = new EventDispatcher();
        $subscriber = new EventHandlerSubscriber();
        $event = new EventHandlerDispatcher($RequestEventArgs);
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, EventHandlerDispatcher::NAME);
        AssertUtils::assertThat($event->getObject())::isNotNull();
        AssertUtils::assertThat($event->getObject()->getTopology())::isNotNull();
        AssertUtils::assertThat($event->getObject()->getTopology())::isInstanceOf(Topology::class);
        AssertUtils::assertThat($event->getObject())::isInstanceOf(TopologyUpdatedEventArgs::class);
        // Let's say we want save change of Etag from Et57_updating to Et58_upgraded via subscriber and run assertion on the same
        AssertUtils::assertThat($event->getObject()->getTopology()->getEtag())::isIdenticalTo("Et58_upgraded"); // <- run the test as it is, alter the string and run it again (expecting to fail)
    }

    public function testCanTopologySuccessiveUpdatedEventArgs(){
        $topology = new Topology();
        $topology->setEtag('Et57_updating');
        $RequestEventArgs = new TopologyUpdatedEventArgs($topology);
        $dispatcher = new EventDispatcher();
        $subscriber = new EventHandlerSubscriber();
        $event = new EventHandlerDispatcher($RequestEventArgs);
        $dispatcher->addSubscriber($subscriber);
        AssertUtils::assertThat($topology->getEtag())::isIdenticalTo("Et57_updating"); // <- before subscriber
        $dispatcher->dispatch($event, EventHandlerDispatcher::NAME);
        AssertUtils::assertThat($event->getObject())::isNotNull();
        AssertUtils::assertThat($event->getObject()->getTopology())::isNotNull();
        AssertUtils::assertThat($event->getObject()->getTopology())::isInstanceOf(Topology::class);
        AssertUtils::assertThat($event->getObject())::isInstanceOf(TopologyUpdatedEventArgs::class);
        // Let's say we want save change of Etag from Et57_updating to Et58_upgraded via subscriber and run assertion on the same
        AssertUtils::assertThat($event->getObject()->getTopology()->getEtag())::isIdenticalTo("Et58_upgraded"); // <- after subscriber
    }

    public function testCanAddBeforeStoreListener(){
        $handler  = new BeforeStoreEventArgs(null,'eESd4',new \stdClass());
        $dispatcher = new EventDispatcher();
        $subscriber = new InMemoryDocSessionSubscriber();
        $event = new InMemoryDocSessionDispatcher($handler);
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, 'onBeforeStore.add'); // add and remove suffix happen behind the scene
        AssertUtils::assertThat($event)::isNotEmpty();
        AssertUtils::assertThat($event)::isObject();
        AssertUtils::assertThat($event)::isInstanceOf(Event::class);
        AssertUtils::assertThat($event->getObject())::isInstanceOf(BeforeStoreEventArgs::class);
        AssertUtils::assertThat($event->getObject()->getDocumentId())::isNotNull();
        AssertUtils::assertThat($event->getObject()->getSession())::isNotNull();
        AssertUtils::assertThat($event->getObject()->getDocumentId())::isIdenticalTo('eESd4');
    }
}
