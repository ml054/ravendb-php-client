<?php

namespace RavenDB\Client\Documents\Session;

use Symfony\Contracts\EventDispatcher\Event;

class SessionListener
{
    public function onAction(Event $event){
        dd("hello World");
    }
}
