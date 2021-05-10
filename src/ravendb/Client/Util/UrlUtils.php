<?php

namespace RavenDB\Client\Util;

class UrlUtils
{
    /**
     * @throws \Exception
     */
    public static function pathBuilder(array $params){
        if(!is_array($params)) throw new \Exception("You need to provide params as an array");
        $and = count($params) > 1 ? "&" : "";
        return $and.http_build_query($params);
    }
}
