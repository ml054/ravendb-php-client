<?php

namespace RavenDB\Client\Http;
// TODO : REMOVE ALL JAVA Map approach -> php Assoc Array //
// SUGGEST : IF POSSIBLE TO SET AN INTERFACE

class ClusterTopology
{
    public string $last_node_id;
    public ?string $topology_id;
    public string $etag;
    public array|object $members;
    public array|object $promotables;
    public array|object $watchers;
    public array|object $all_nodes;

    // TODO Subject to improvement : Goal : Dynamically create/assign the properties. To remove getter/setter and all properties public
    public function mapOptions(array $response): self
    {
        foreach ($response["topology"] as $field => $value) {
            $this->{$field} = $value;
        }
        // TODO : INJECT THE RESPONSE
        return $this;
    }
}