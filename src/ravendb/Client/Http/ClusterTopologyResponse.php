<?php
namespace RavenDB\Client\Http;
// TODO INJECT THE CLASS RESPONSE

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class ClusterTopologyResponse
{
    public string $leader;
    public string $nodeTag;
    public ClusterTopology $topology;
    public string $etag;
    private NodeStatus $status;
    private object $jsonObject;

    public function __construct(object $json)
    {
        $this->jsonObject = $json;
    }

    public function response(){
        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $clusterTopology = new ClusterTopology();
        /// GENERATING THE CLUSTER TOPOLOGY SETTERS
        foreach( $this->jsonObject->Topology as $property => $value ){
            /// CAMEL CASING
            $normalize = $nameConverter->denormalize($property);
            $methods = 'set'.ucfirst($normalize);
            if(property_exists($clusterTopology,$normalize)){
                $clusterTopology->$methods($value);
            }
        }
        $this->setNodeTag($this->jsonObject->NodeTag);
        $this->setLeader($this->jsonObject->Leader);
        $this->setEtag($this->jsonObject->Etag);
        $this->setTopology($clusterTopology);
        /*if(null !== (array)count($this->jsonObject->status)){
            // TODO GENERA THE STATUS DATA FROM NON EMPTY OBJECT
        }*/
        return $this;
    }
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