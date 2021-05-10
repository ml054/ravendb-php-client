<?php

namespace RavenDB\Client;

class Defaults
{
    // TODO CHECK WITH TECH FOR NOW RETURNING THE GETTYPE
    public static function defaultValue($clazz){
        return gettype($clazz);
    }
}
