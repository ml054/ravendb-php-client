<?php

namespace RavenDB\Client\Util;

use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Primitives\VoidArgs;

// TODO MIGRATION : EventHandler class
interface IDisposalNotification extends Closable
{
    public function addAfterCloseListener(VoidArgs $event): void;

    public function removeAfterCloseListener(VoidArgs $event);

    public function isDisposed(): bool;

    public function close();
}
