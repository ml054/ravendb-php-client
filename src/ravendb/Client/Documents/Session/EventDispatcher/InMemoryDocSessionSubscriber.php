<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher;

use RavenDB\Client\Constants;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeStoreEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class InMemoryDocSessionSubscriber
 * @package RavenDB\Client\Documents\Session\EventDispatcher
 */
class InMemoryDocSessionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents():array
    {
        return [
            "on.before.event.args" => "onBeforeEventArgs",
            "on.before.conversion.to.document" => "onBeforeConversionToDocument",
        ];
    }

    public function onBeforeEventArgs(InMemoryDocSessionDispatcher $event){
        $object = $event->getObject();
        if($object instanceof BeforeStoreEventArgs){

        }
    }

    public function onBeforeConversionToDocument(InMemoryDocSessionDispatcher $event){
        // code here
    }

}
