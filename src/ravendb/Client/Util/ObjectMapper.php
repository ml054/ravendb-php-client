<?php


namespace RavenDB\Client\Util;

// TODO CROSS-CLASS TRAIT - THIS IS DRAFT SUBJECT TO CHANGES
use RavenDB\Client\Extensions\JsonExtensions;

trait ObjectMapper
{
    public static function mapper(): JsonExtensions
    {
       return JsonExtensions::getDefaultMapper();
    }
}