<?php

namespace RavenDB\Client\Util;

class Semaphore
{
    // TODO CHECK FOR EQUIV LIB IN PHP. CHECK WITH TECHTEAM IF NEEDED
    private int $permits;
    public function __construct(int $permits)
    {
        $this->permits = $permits;
    }
}
