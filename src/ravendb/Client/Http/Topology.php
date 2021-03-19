<?php

namespace RavenDB\Client\Http;
// TODO : CHECK WITH MARCIN IF TO INJECT IN THE CLUSTER TOPOLOGY RESPONSE
class Topology
{
    private string $etag;
    private ServerNode $nodes;

    public function getEtag(): string
    {
        return $this->etag;
    }

    public function setEtag(string $etag):void{
        $this->etag = $etag;
    }

    public function getNodes():ServerNode{
        return $this->nodes;
    }

    public function setNodes(ServerNode $nodes):void{
        $this->nodes = $nodes;
    }
}