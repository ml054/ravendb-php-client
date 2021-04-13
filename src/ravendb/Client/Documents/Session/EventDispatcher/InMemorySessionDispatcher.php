<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
// TODO WORK IN PROGRESS SUBJECT TO CHANGES

/**
 * Trait Dispatcher
 * @package RavenDB\Client\Documents\Session\EventDispatcher
 *
 */
trait InMemorySessionDispatcher
{
    /**
     * @throws \Exception
     */
    public function add(object $handler,string $trigger,$dispatch=true){
        $dispatcher = new EventDispatcher();
        $subscriber = new InMemoryDocSessionSubscriber();
        $event = new InMemoryDocSessionDispatcher($handler);
        $dispatcher->addSubscriber($subscriber);
        if(true === $dispatch){
            $dispatcher->dispatch($event, $trigger."add");
        }
    }

    /**
     * @throws \Exception
     */
    public function remove($handler){
        $dispatcher = new EventDispatcher();
        $subscriber = new EventHandlerSubscriber();
        $event = new EventHandlerDispatcher($handler);
        $event->stopPropagation();
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, EventHandlerDispatcher::NAME);
        if(!$event->isPropagationStopped()){
            throw new \Exception('The event should be stopped');
        }
    }

    
}
