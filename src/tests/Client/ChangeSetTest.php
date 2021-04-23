<?php
namespace RavenDB\Tests\Client\Sessions;
use RavenDB\Client\Documents\Session\EventDispatcher\ChangeSetDispatcher;
use RavenDB\Client\Documents\Session\EventDispatcher\ChangeSetSubscriber;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeRequestEventArgs;
use RavenDB\Client\Util\EventNameHolder;
use RavenDB\Tests\Client\RemoteTestBase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ChangeSetTest extends RemoteTestBase
{
    use EventNameHolder;
    public function testCanRunEventsSequence(){
        $handler  = new BeforeRequestEventArgs('database_name','url',55);
        $dispatcher = new EventDispatcher();
        $subscriber = new ChangeSetSubscriber();
        $event = new ChangeSetDispatcher($handler);
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, 'onChangeSetSequenceBeforeStore');
    }
}
