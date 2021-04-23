<?php

namespace RavenDB\Client\Util;
use Ramsey\Uuid\Uuid;
class RaftIdGenerator
{
    public function __construct(){ }

    public static function newId(): string
    {
        return Uuid::uuid4()->toString();
    }

    public static function ignoreId(): string
    {
        return ""; // empty string
    }

}
