<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher;
use RavenDB\Client\Constants;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeStoreEventArgs;
use Symfony\Contracts\EventDispatcher\Event;

class ChangeSetDispatcher extends Event
{
    public const NAME = Constants::CHANGE_SET_DISPATCHER;
    protected object $data;

    public function __construct(object $data)
    {
        $this->data = $data;
    }

    public function getObject():object
    {
        return $this->data;
    }
}
