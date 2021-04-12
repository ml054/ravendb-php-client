<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher;

/**
 * Interface IEventHandler
 * @package RavenDB\Client\Documents\Session\EventDispatcher
 * Interface for events sharing the exact same property to be mapped at the dispatcher
 */
interface IEventHandler
{
    public function getDatabase(): string;
    public function getUrl(): string;
    public function getAttemptNumber(): int;
}
