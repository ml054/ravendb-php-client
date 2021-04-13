<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher;

use RavenDB\Client\Constants;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeRequestEventArgs;
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
            "onBeforeStore.add" => "onBeforeStoreAdd"
        ];
    }
    public function onBeforeStoreAdd(InMemoryDocSessionDispatcher $event){
        $object = $event->getObject();
        if($object instanceof BeforeRequestEventArgs){
            // feed the session here or any operation to be triggered. no limit
        }
    }
}
