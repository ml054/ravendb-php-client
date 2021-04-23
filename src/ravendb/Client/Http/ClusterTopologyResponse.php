<?php
namespace RavenDB\Client\Http;
// TODO INJECT THE CLASS RESPONSE
use Doctrine\Common\Collections\ArrayCollection;

class ClusterTopologyResponse
{
    public string $leader;
    public string $nodeTag;

    public ClusterTopology $topology;
    public string|int $etag;
    private ArrayCollection $status;

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
     * @return ArrayCollection
     */
    public function getStatus(): ArrayCollection
    {
        return $this->status;
    }

    /**
     * @param NodeStatus $status
     */
    public function setStatus(ArrayCollection $status): void
    {
        $this->status = $status;
    }
}