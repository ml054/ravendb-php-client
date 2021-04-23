<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher;

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
            "onSequenceBeforeStore"=>[
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
