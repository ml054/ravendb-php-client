<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ChangeSetSubscriber
 * @package RavenDB\Client\Documents\Session\EventDispatcher
 */
class ChangeSetSubscriber implements EventSubscriberInterface
{
    private array $dataChangeSet;
    public static function getSubscribedEvents():array
    {
        return [
            "onChangeSetSequenceBeforeStore"=>[
                ['beforeStoreListener', 5],
                ['afterStoreListener', -5]
            ],
        ];
    }

    public function beforeStoreListener(ChangeSetDispatcher $event){
        $this->dataChangeSet["original"][] = "A";
    }

    public function afterStoreListener(ChangeSetDispatcher $event){
        dump($this->dataChangeSet);
    }
}
