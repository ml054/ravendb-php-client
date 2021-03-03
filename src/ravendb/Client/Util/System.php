<?php

namespace RavenDB\Client\Util;

class System
{
    public static function getenv($ENV): bool|array|string
    {
        return getenv($ENV);
    }
}
