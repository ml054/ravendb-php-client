<?php
namespace RavenDB\Client\Util;

use RavenDB\Client\Extensions\JsonExtensions;

trait ObjectMapper
{
    public function mapper(): JsonExtensions
    {
       return JsonExtensions::getDefaultMapper();
    }

    /**
     * @throws \Exception
     */
    public function serialize(?object $object=null): void {
        if(!is_object($object)) throw new \Exception("Data source must be an object");
        $serializer = JsonExtensions::storeSerializer();
        $serializer->serialize($object,'json');
    }
}