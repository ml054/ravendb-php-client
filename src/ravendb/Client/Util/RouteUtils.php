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
    public static function store(DocumentStore|ServerNode $store, string $route, array $params):string{
        $url = null;
        if($store instanceof DocumentStore){
            $url = $store->getUrls()[0];
        }elseif($store instanceof ServerNode){
            $url = $store->getUrl();
        }
        return $url.$route.http_build_query($params);
    }

    public static function request(string $url, string $route, array $params):string{
        return $url.$route.http_build_query($params);
    }
}
