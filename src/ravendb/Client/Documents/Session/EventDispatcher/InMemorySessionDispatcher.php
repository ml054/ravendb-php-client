<?php
namespace RavenDB\Client\Documents\Session\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Trait Dispatcher
 * @package RavenDB\Client\Documents\Session\EventDispatcher
 */
trait InMemorySessionDispatcher
{
    /**
     * @throws \Exception
     */
    public function add($handler,string $eventName){
        $dispatcher = new EventDispatcher();
        $subscriber = new InMemoryDocSessionSubscriber();
        $event = new InMemoryDocSessionDispatcher($handler);
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, $eventName);
    }

    /**
     * @throws \Exception
     */
    public function remove($handler,string $eventName){
        $dispatcher = new EventDispatcher();
        $subscriber = new InMemoryDocSessionSubscriber();
        $event = new InMemoryDocSessionDispatcher($handler);
        $event->stopPropagation();
        $dispatcher->addSubscriber($subscriber);
        return $dispatcher->dispatch($event, $eventName);
    }
}
