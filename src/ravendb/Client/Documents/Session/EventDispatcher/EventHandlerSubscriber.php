<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EventHandlerSubscriber
 * @package RavenDB\Client\Documents\Session\EventDispatcher
 * Centralized event handler
 */
class EventHandlerSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents():array
    {
        return [
            EventHandlerDispatcher::NAME => 'onEventProceedFunction'
        ];
    }
    public function onEventProceedFunction(EventHandlerDispatcher $event){
        $object = $event->getObject();
    }
}
