<?php

namespace RavenDB\Client\Http;
// TODO INJECT THE CLASS RESPONSE
class ClusterTopologyResponse
{
    private string $leader;
    private string $node_tag; // TODO CHANGING TO snake_case. To check with Marcin. As per php OOP standards
    public ClusterTopology $topology;
    private string $etag;
    private NodeStatus $status;
    // TODO : ADDING THE RESPONSE BODY
    private string|array $topology_response;

    public function getLeader(): string
    {
        return $this->leader;
    }

    public function setLeader(string $leader): void
    {
        $this->leader = $leader;
    }

    public function getNodeTag(): string
    {
        return $this->node_tag;
    }

    public function setNodeTag(string $nodeTag): void
    {
        $this->node_tag = $nodeTag;
    }

    public function getTopology(): ClusterTopology
    {
        return $this->topology;
    }

    public function setTopology(ClusterTopology $topology): void
    {
        $this->topology = $topology;
    }

    public function getEtag(): string
    {
        return $this->etag;
    }

    public function setEtag(string $etag): void
    {
        $this->etag = $etag;
    }

    public function getStatus(): NodeStatus
    {
        return $this->status;
    }

    public function setStatus(NodeStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getTopologyResponse(): string|array
    {
        return $this->topology_response;
    }

    /**
     * @param string|array $topology_response
     */
    public function setTopologyResponse(string|array $topology_response): void
    {
        $this->topology_response = $topology_response;
    }

}
/* PHP-MIGRATION-STATUS : EMPTY SOURCE CLASS BODY - MEANS ALL RESOURCES ARE IMPORTED. TO CLEAN ONCE VALIDATED
public class ClusterTopologyResponse {

}
 * */