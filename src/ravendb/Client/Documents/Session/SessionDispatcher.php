<?php

namespace RavenDB\Client\Documents\Session;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SessionDispatcher
{
    public static function dispatcher(){
        return new EventDispatcher();
    }

}
