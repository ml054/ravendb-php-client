<?php

namespace RavenDB\Client\Util;

use RavenDB\Client\Http\ServerNode;

class RouteUtils
{

    public static function node(ServerNode $node,string $route,array $params):string{
        return $node->getUrl().$route.http_build_query($params);
    }
}
