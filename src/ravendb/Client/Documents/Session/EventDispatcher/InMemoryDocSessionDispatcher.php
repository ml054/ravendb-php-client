<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher;
use RavenDB\Client\Constants;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\FailedRequestEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\TopologyUpdatedEventArgs;
use Symfony\Contracts\EventDispatcher\Event;

class InMemoryDocSessionDispatcher extends Event
{
    public const NAME = Constants::EVENT_INMEMO_KEY;
    protected FailedRequestEventArgs|IEventHandler|TopologyUpdatedEventArgs $object;

    public function __construct(FailedRequestEventArgs|IEventHandler|TopologyUpdatedEventArgs $object)
    {
        $this->object = $object;
    }

    public function getObject(): FailedRequestEventArgs|IEventHandler|TopologyUpdatedEventArgs
    {
        return $this->object;
    }
}
