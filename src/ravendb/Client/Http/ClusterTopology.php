<?php

namespace RavenDB\Client\Http;
// TODO : REMOVE ALL JAVA Map approach -> php Assoc Array
class ClusterTopology
{
    public string $last_node_id;
    public ?string $topology_id;
    private string $etag;
    private array|object $members;
    private array|object $promotables;
    private array|object $watchers;
    public array|object $all_nodes;
    // TODO Subject to improvement : Goal : Dynamically create/assign the properties. To remove getter/setter and all properties public
    public function mapOptions(array $topologyData):self
    {
        foreach ($topologyData["topology"] as $field=>$value){
            $this->{$field} = $value;
        }
        return $this;
    }

    public function getAllNodes(): array
    {
       // TODO : Standard php array to implement
    }

    /**
     * @return string
     */
    public function getLastNodeId(): string
    {
        return $this->last_node_id;
    }

    /**
     * @param string $last_node_id
     */
    public function setLastNodeId(string $last_node_id): void
    {
        $this->last_node_id = $last_node_id;
    }

    /**
     * @return string
     */
    public function getTopologyId(): string
    {
        return $this->topology_id;
    }

    /**
     * @param string $topology_id
     */
    public function setTopologyId(string $topology_id): void
    {
        $this->topology_id = $topology_id;
    }



    public function getMembers(): array|object
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

    /**
     * TODO check with Marcin. Using regular setter for setAllNodes
     * @param array|object $all_nodes
     */
    public function setAllNodes(object|array $all_nodes): void
    {
        $this->all_nodes = $all_nodes;
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