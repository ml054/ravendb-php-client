<?php

namespace RavenDB\Tests\Client\Sessions;
use RavenDB\Client\Constants;
use RavenDB\Tests\Client\Poc;
use Symfony\Contracts\EventDispatcher\Event;

class EntityDispatcher extends Event
{
    public const NAME=Constants::EVENT_ENTITY_TEST_KEY;
    protected Poc $entity;

    public function __construct(Poc $entity)
    {
        $this->entity = $entity;
    }
    public function getEntity(): Poc
    {
        return $this->entity;
    }
}
