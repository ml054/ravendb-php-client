<?php

namespace RavenDB\Client\Http;
// TODO INJECT THE CLASS RESPONSE
class ClusterTopologyResponse
{
    private string $leader;
    private string $nodeTag;
    public ClusterTopology $topology;
    private string $etag;
    private NodeStatus $status;

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
        return $this->nodeTag;
    }

    public function setNodeTag(string $nodeTag): void
    {
        $this->nodeTag = $nodeTag;
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
}
/* PHP-MIGRATION-STATUS : EMPTY SOURCE CLASS BODY - MEANS ALL RESOURCES ARE IMPORTED. TO CLEAN ONCE VALIDATED
public class ClusterTopologyResponse {

}
 * */