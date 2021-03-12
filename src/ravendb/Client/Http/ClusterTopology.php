<?php

namespace RavenDB\Client\Http;
// TODO : REMOVE ALL JAVA Map approach -> php Assoc Array
class ClusterTopology
{
    private string $lastNodeId;
    private string $topologyId;
    private string $etag;
    // TODO : AS INSTRUCTED java Map -> php Array : file update in progress. Standard php dev for specific java language lib
    private array $members;
    private array $promotables;
    private array $watchers;

    public function contains(string $node): bool
    {
        /*TODO: Standard PHP approach

        if ($this->members !== null && $this->members->containsKey($node)) {
            return true;
        }
        if ($this->promotables !== null && $this->promotables->containsKey($node)) {
            return true;
        }
        return $this->watchers !== null && $this->watchers->containsKey($node);*/
    }

    public function getUrlFromTag(?string $tag): string|null
    {
        if ($tag === null) {
            return null;
        }
       /* TODO: Standard php aproach to implement
        if ($this->members !== null && $this->members->containsKey($tag)) {
            return $this->members->get($tag);
        }

        if ($this->promotables !== null && $this->promotables->containsKey($tag)) {
            return $this->promotables->get($tag);
        }

        if ($this->watchers !== null && $this->watchers->containsKey($tag)) {
            return $this->watchers->get($tag);
        }*/
        return null;
    }


    public function getAllNodes(): array
    {
       // TODO : Standard php array to implement
         $result = [];
        if ($this->members !== null) {
           // foreach($this->members)
        }

        if ($this->promotables !== null) {
            // TODO FOR LOOP
            /*for (Map.Entry<String, String> entry : promotables.entrySet()) {
                result.put(entry.getKey(), entry.getValue());
            }*/
        }

        if ($this->watchers !== null) {
            // TODO FOR LOOP
            /*for (Map.Entry<String, String> entry : watchers.entrySet()) {
                result.put(entry.getKey(), entry.getValue());
            }*/
        }
        return $result;
    }

    public function getLastNodeId(): string
    {
        return $this->lastNodeId;
    }

    public function setLastNodeId(string $lastNodeId): void
    {
        $this->lastNodeId = $lastNodeId;
    }

    public function getTopologyId(): string
    {
        return $this->topologyId;
    }

    public function setTopologyId(string $topologyId): void
    {
        $this->topologyId = $topologyId;
    }

    public function getMembers(): array
    {
        return $this->members;
    }

    public function setMembers($members): void
    {
        $this->members = $members;
    }

    public function getPromotables(): array
    {
        return $this->promotables;
    }

    public function setPromotables(array $promotables): void
    {
        $this->promotables = $promotables;
    }

    public function getWatchers(): array
    {
        return $this->watchers;
    }

    public function setWatchers(array $watchers): void
    {
        $this->watchers = $watchers;
    }

    public function getEtag(): string
    {
        return $this->etag;
    }

    public function setEtag(string $etag): void
    {
        $this->etag = $etag;
    }
}
/* PHP-MIGRATION-STATUS : EMPTY SOURCE CLASS BODY - MEANS ALL RESOURCES ARE IMPORTED. TO CLEAN ONCE VALIDATED
class ClusterTopology {

}
 * */