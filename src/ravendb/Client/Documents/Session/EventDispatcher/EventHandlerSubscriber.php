<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher;

use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\TopologyUpdatedEventArgs;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Http\Topology;
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
    /**
     * Can clearly be improved with implementing session tokens etc... this is light version for the test purpose. Subject to evoluate
    */
    public function onEventProceedFunction(EventHandlerDispatcher $event){
        $object = $event->getObject();
        if($object instanceof TopologyUpdatedEventArgs){
            $topology = $object->getTopology();
            if($topology instanceof Topology){
                $topology->setEtag("Et58_upgraded");
            }
        }
    }
}
