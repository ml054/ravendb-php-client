<?php
namespace RavenDB\Client\Http;
// TODO INJECT THE CLASS RESPONSE
use Symfony\Component\Serializer\Annotation\SerializedName;

class ClusterTopologyResponse
{
    #[SerializedName("Leader")]
    public string $leader;
    #[SerializedName("Leader")]
    public string $nodeTag;

    public ClusterTopology $topolgy;
    public string $etag;
    private NodeStatus $status;

    /**
     * @return string
     */
    public function getLeader(): string
    {
        return $this->leader;
    }

    /**
     * @param string $leader
     */
    public function setLeader(string $leader): void
    {
        $this->leader = $leader;
    }

    /**
     * @return string
     */
    public function getNodeTag(): string
    {
        return $this->nodeTag;
    }

    /**
     * @param string $nodeTag
     */
    public function setNodeTag(string $nodeTag): void
    {
        $this->nodeTag = $nodeTag;
    }

    /**
     * @return ClusterTopology
     */
    public function getTopology(): ClusterTopology
    {
        return $this->topology;
    }

    /**
     * @param ClusterTopology $topology
     */
    public function setTopology(ClusterTopology $topology): void
    {
        $this->topology = $topology;
    }

    /**
     * @return string
     */
    public function getEtag(): string
    {
        return $this->etag;
    }

    /**
     * @param string $etag
     */
    public function setEtag(string $etag): void
    {
        $this->etag = $etag;
    }

    /**
     * @return NodeStatus
     */
    public function getStatus(): NodeStatus
    {
        return $this->status;
    }

    /**
     * @param NodeStatus $status
     */
    public function setStatus(NodeStatus $status): void
    {
        $this->status = $status;
    }
}