<?php

namespace RavenDB\Client\Http;

use RavenDB\Client\Util\Map;

class ClusterTopology
{
    private string $lastNodeId;
    private string $topologyId;
    private string $etag;
    // TODO : JAVA IMPLEMENTATION OF Mapping TO ACCESS DATA STRUCTURE THE SAME WAY IN PHP. NOW POSSIBLE TO CONVERT SUCH VARIABLES : private Map<String, String> members;
    private Map|array $members;
    private Map|array $promotables;
    private Map|array $watchers;

    public function contains(string $node): bool
    {
        if ($this->members !== null && $this->members->containsKey($node)) {
            return true;
        }
        if ($this->promotables !== null && $this->promotables->containsKey($node)) {
            return true;
        }
        return $this->watchers !== null && $this->watchers->containsKey($node);
    }

    public function getUrlFromTag(?string $tag): string|null
    {
        if ($tag === null) {
            return null;
        }
        if ($this->members !== null && $this->members->containsKey($tag)) {
            return $this->members->get($tag);
        }

        if ($this->promotables !== null && $this->promotables->containsKey($tag)) {
            return $this->promotables->get($tag);
        }

        if ($this->watchers !== null && $this->watchers->containsKey($tag)) {
            return $this->watchers->get($tag);
        }
        return null;
    }


    public function getAllNodes(): Map
    {
        $result = []; // TODO CHECK HOW TO MAP THE RESULT IN A HashMap array : Map<String, String> result = new HashMap<>();
        if ($this->members !== null) {
            // TODO FOR LOOP
            /* for (Map.Entry<String, String> entry : members.entrySet()) {
                 result.put(entry.getKey(), entry.getValue());
             }*/
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

    public function getMembers(): Map
    {
        return $this->members;
    }

    public function setMembers($members): void
    {
        $this->members = $members;
    }

    public function getPromotables(): Map
    {
        return $this->promotables;
    }

    public function setPromotables(array $promotables): void
    {
        $this->promotables = $promotables;
    }

    public function getWatchers(): Map
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