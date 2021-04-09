<?php

namespace RavenDB\Client\Primitives;

interface EventHandler extends EventArgs
{
    /**
     * Handle event
     * sender Event sender, event Event to send
     * @param object $sender
     * @param $event
     */
public function handle(object $sender, $event);
}
