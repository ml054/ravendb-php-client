<?php

namespace RavenDB\Client\Http;
// TODO : IMPLEMENT THE FIRST DRAFT OF THE MAPPER

class ClusterTopology
{
    public string $lastNodeId;
    public ?string $topologyId;
    public string|int $etag;
    public array|object $members;
    public array|object $promotables;
    public array|object $watchers;
    public array|object $allNodes;

    /**
     * @return string
     */
    public function getLastNodeId(): string
    {
        return $this->lastNodeId;
    }

    /**
     * @param string $lastNodeId
     */
    public function setLastNodeId(string $lastNodeId): void
    {
        $this->lastNodeId = $lastNodeId;
    }

    /**
     * @return string|null
     */
    public function getTopologyId(): ?string
    {
        return $this->topologyId;
    }

    /**
     * @param string|null $topologyId
     */
    public function setTopologyId(?string $topologyId): void
    {
        $this->topologyId = $topologyId;
    }

    /**
     * @return string|int
     */
    public function getEtag(): string|int
    {
        return $this->etag;
    }

    /**
     * @param string|int $etag
     */
    public function setEtag(string|int $etag): void
    {
        $this->etag = $etag;
    }

    /**
     * @return array|object
     */
    public function getMembers(): object|array
    {
        return $this->members;
    }

    /**
     * @param array|object $members
     */
    public function setMembers(object|array $members): void
    {
        $this->members = $members;
    }

    /**
     * @return array|object
     */
    public function getPromotables(): object|array
    {
        return $this->promotables;
    }

    /**
     * @param array|object $promotables
     */
    public function setPromotables(object|array $promotables): void
    {
        $this->promotables = $promotables;
    }

    /**
     * @return array|object
     */
    public function getWatchers(): object|array
    {
        return $this->watchers;
    }

    /**
     * @param array|object $watchers
     */
    public function setWatchers(object|array $watchers): void
    {
        $this->watchers = $watchers;
    }

    /**
     * @return array|object
     */
    public function getAllNodes(): object|array
    {
        return $this->allNodes;
    }

    /**
     * @param array|object $allNodes
     */
    public function setAllNodes(object|array $allNodes): void
    {
        $this->allNodes = $allNodes;
    }


}