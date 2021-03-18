<?php

namespace RavenDB\Client\Http;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ClusterTopologyResponse extends \ArrayObject
{
    private string $leader;
    private string $nodeTag;
    private ClusterTopology $topology;
    private string $etag;
    private NodeStatus $status;
    // TODO : TO HAVE THE TRANSFORMER WORK.
    private ?string $attributes;
    // TODO CHECK WITH MARCIN : BLOCKING POINT : in php a child class cannot have 2 parents
    private array $children;

    public function __construct(?string $attributes=null)
    {
        $this->attributes = $attributes;
    }

    /*public function __get($id): array
    {
        return $this->children[$id];
    }*/

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

    // TODO : Serializer
    public static function serializer($result,$property){
        $data = serialize($result);
        $unserialize = json_decode(unserialize($data,['allowed_access'=>true]));
        $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
            ->disableExceptionOnInvalidPropertyPath()
            ->getPropertyAccessor();
        return $propertyAccessor->getValue($unserialize, $property);
    }
}
/* PHP-MIGRATION-STATUS : EMPTY SOURCE CLASS BODY - MEANS ALL RESOURCES ARE IMPORTED. TO CLEAN ONCE VALIDATED
public class ClusterTopologyResponse {

}
 * */