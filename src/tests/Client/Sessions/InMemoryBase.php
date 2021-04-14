<?php

namespace RavenDB\Tests\Client\Sessions;

use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeStoreEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\InMemorySessionDispatcher;
use RavenDB\Client\Util\EventNameHolder;

class InMemoryBase
{
    use InMemorySessionDispatcher;
    use EventNameHolder;

    public function addBeforeStoreListener(BeforeStoreEventArgs $handler){
       $this->add($handler,$this->eventNameOnBeforeStore);
    }

    public function removeBeforeStoreListener(BeforeStoreEventArgs $handler){
       $this->remove($handler,$this->eventNameOnBeforeStore);
    }
}
