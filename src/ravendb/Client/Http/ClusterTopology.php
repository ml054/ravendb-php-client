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
        if($this->members && $this->members[$node]){
            return true;
        }
        if($this->promotables && $this->promotables[$node]){
            return true;
        }
        return $this->watchers && $this->watchers[$node];
    }

    public function getUrlFromTag(?string $tag): string|null
    {
        if (!$tag) {
            return null;
        }
        if($this->members && $this->members[$tag]){
            return true;
        }
        if($this->promotables && $this->promotables[$tag]){
            return true;
        }
        if($this->watchers && $this->watchers[$tag]){
            return true;
        }

        return null;
    }


    public function getAllNodes(): array
    {
       // TODO : Standard php array to implement
         $result = [];
        if ($this->members !== null) {
         //  foreach($this->members)
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
export class ClusterTopology {

    public lastNodeId: string;
    public topologyId: string;
    public etag: number;

    public members: { [key: string]: string };
    public promotables: { [key: string]: string };
    public watchers: { [key: string]: string };

    public contains(node: string) {
        if (this.members && this.members[node]) {
            return true;
        }
        if (this.promotables && this.promotables[node]) {
            return true;
        }

        return this.watchers && this.watchers[node];
    }

    public getUrlFromTag(tag: string): string {
        if (!tag) {
            return null;
        }

        if (this.members && this.members[tag]) {
            return this.members[tag];
        }

        if (this.promotables && this.promotables[tag]) {
            return this.promotables[tag];
        }

        if (this.watchers && this.watchers[tag]) {
            return this.watchers[tag];
        }

        return null;
    }

    public getAllNodes(): { [tag: string]: string } {
        return Object.assign({}, this.members, this.promotables, this.watchers);
    }
}

 * */