<?php

namespace RavenDB\Client\Util;

use Ds\Map as DsMap;

/*TODO DATA STRUCTURE EXTENSION TO EMULATE JAVA Map.java library. To use anywhere Map is associated to an object from java client **/
class Map
{
    public function __construct(private DsMap $map)
    {
    }

    public function containsKey($key): bool
    {
        return $this->map->hasKey($key);
    }

    public function get($key){
        return $this->map->get($key);
    }

}