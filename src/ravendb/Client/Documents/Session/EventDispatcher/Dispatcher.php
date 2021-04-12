<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
// TODO WORK IN PROGRESS SUBJECT TO CHANGES

/**
 * Trait Dispatcher
 * @package RavenDB\Client\Documents\Session\EventDispatcher
 *
 */
trait Dispatcher
{
    /**
     * @throws \Exception
     */
    public function add($handler){
        $dispatcher = new EventDispatcher();
        $subscriber = new EventHandlerSubscriber();
        $event = new EventHandlerDispatcher($handler);
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, EventHandlerDispatcher::NAME);
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
