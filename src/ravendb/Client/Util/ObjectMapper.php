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

    /**
     * @throws \Exception
     */
    public function serialize(object $object){
        if(!is_object($object)) throw new \Exception("Data source must be an object");
        $serializer = JsonExtensions::storeSerializer();
        return $serializer->serialize($object,'json');
    }
}