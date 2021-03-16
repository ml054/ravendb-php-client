<?php

namespace RavenDB\Client\Http;

class Topology
{
    private string $etag;
    private ServerNode|array $nodes;

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