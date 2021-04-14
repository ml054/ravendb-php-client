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
            "onBeforeStore.add" => "addBeforeStoreListener",
            "onBeforeStore.remove" => "removeBeforeStoreListener",
            "onSequenceBeforeStore"=>[ // THIS DEMONSTRATES AN EVENT CAN MANAGE MULTIPLE LISTENERS IN A SEQUENCE WITH PRIORITY
                ['addBeforeStoreListener', 5],
                ['processingListener', -5],
                ['removeBeforeStoreListener', -10],
            ],
        ];
    }

    public function addBeforeStoreListener(InMemoryDocSessionDispatcher $event){
        dump("addBeforeStoreListener : ".__METHOD__);
    }

    public function processingListener(InMemoryDocSessionDispatcher $event){
        dump("processingListener: ".__METHOD__);
    }

    public function removeBeforeStoreListener(InMemoryDocSessionDispatcher $event){
        dump("removeBeforeStoreListener : ".__METHOD__);
    }
}
