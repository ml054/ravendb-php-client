<?php

namespace RavenDB\Client\DataBind\Node;

class ObjectNode
{
    // TODO Json extension for serializing/querying document within session. Empty class by design for now
    public function get(?string $parentKey=null,?string $childKey=null){
        return $parentKey; // Just for the purpose of testing. To be extended
    }
}
