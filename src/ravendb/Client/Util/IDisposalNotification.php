<?php

namespace RavenDB\Client\Util;

use RavenDB\Client\Primitives\Closable;
// TODO MIGRATION : EventHandler class
interface IDisposalNotification extends Closable
{
    public function addAfterCloseListener(EventHandler $event): void;

    public function removeAfterCloseListener(EventHandler $event);

    public function isDisposed(): bool;

    public function close();
}
