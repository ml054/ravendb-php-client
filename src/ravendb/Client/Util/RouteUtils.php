<?php

namespace RavenDB\Client\Util;

use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Http\ServerNode;

class RouteUtils
{

    public static function node(ServerNode $node,string $route,array $params):string{
        return $node->getUrl().$route.http_build_query($params);
    }

    /**
     * @throws \Exception
     */
    public static function store(DocumentStore $store, string $route, array $params):string{
        // TODO MANAGE THE ARRAY PARAMETER
        if(is_array($store->getUrls()) && count($store->getUrls()) > 1) throw new \Exception('Array processing is not yet in place');
        return $store->getUrls()[0].$route.http_build_query($params);
    }
}
