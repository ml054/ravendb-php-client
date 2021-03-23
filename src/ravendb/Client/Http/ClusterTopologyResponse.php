<?php

namespace RavenDB\Client\Http;
// TODO INJECT THE CLASS RESPONSE
use phpDocumentor\Reflection\Types\Self_;

class ClusterTopologyResponse
{
    public string $leader;
    public string $node_tag; // TODO CHANGING TO snake_case. To check with Marcin. As per php OOP standards
    //public ClusterTopology $topology;
    public string $etag;
  //  public NodeStatus $status;
    // TODO : ADDING THE RESPONSE BODY

    public function mapOptions(array $json):self{
        $topology = new ClusterTopology();
        $topology->mapOptions($json);
        $classVar = get_class_vars(self::class);

        foreach ($classVar as $field => $value) {
            $this->{$field} = $json[$field];
        }
        return $this;
    }

}
/* PHP-MIGRATION-STATUS : EMPTY SOURCE CLASS BODY - MEANS ALL RESOURCES ARE IMPORTED. TO CLEAN ONCE VALIDATED
public class ClusterTopologyResponse {

}
 * */