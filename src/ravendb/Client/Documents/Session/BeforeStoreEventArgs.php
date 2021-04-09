<?php

namespace RavenDB\Client\Documents\Session;

use RavenDB\Tests\Client\Sessions\SessionObject;
use Symfony\Contracts\EventDispatcher\Event;

class BeforeStoreEventArgs extends Event
{
    public const NAME="before.store.eventargs";

    protected SessionObject $session;

    public function __construct(SessionObject $session)
    {
        dd(__CLASS__);
        $this->session = $session;
    }
    /**
     * @return SessionObject
     */
    public function getSession(): SessionObject
    {
        return $this->session;
    }
}
