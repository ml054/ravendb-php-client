<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Class ChangeSetSubscriber
 * @package RavenDB\Client\Documents\Session\EventDispatcher
 */
class ChangeSetSubscriber implements EventSubscriberInterface
{
    private ArrayCollection $dataChangeSet;
    public static function getSubscribedEvents():array
    {
        // TODO : IMPLEMENT IN THE LOAD METHOD
        return [
            "onChangeSetSequenceBeforeStore"=>[
                ['beforeStoreListener', 5],
                ['afterStoreListener', -5]
            ],
        ];
    }

    public function beforeStoreListener(ChangeSetDispatcher $event){
        $this->dataChangeSet = new ArrayCollection(["current"=>$event]);
    }

    public function afterStoreListener(ChangeSetDispatcher $event){
        $changes = $this->dataChangeSet->add(["changes"=>"Here"]);
        dd($this->dataChangeSet);
    }
}
