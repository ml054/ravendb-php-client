<?php
namespace RavenDB\Client\Serverwide\Commands;
use HttpResponseException;
use RavenDB\Client\Http\ClusterTopology;
use RavenDB\Client\Http\ClusterTopologyResponse;
use RavenDB\Client\Http\NodeStatus;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class GetClusterTopologyCommand extends RavenCommand
{
    private null|string $_debugTag;

    public function __construct(?string $debugTag = null)
    {
        $this->_debugTag = $debugTag === null ? null : $debugTag;
    }

    public function createRequest(ServerNode $node): array
    {
        $url = $node->getUrl() . "/cluster/topology";
        if ($this->_debugTag !== null) $url .= "?" . $this->_debugTag;
        return [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ];
    }
    // TODO : AFTER REVIEW : create a mapper standalone that will mount and return object dependencies and NOT stdclass from the response. In progress
    // In 2 steps : response -> Symfony Normalizer
    public function setResponse(string|array $response, bool $fromCache): ClusterTopologyResponse
    {
        // TODO : THROWING A REGULAR EXCEPTION
        if (null === $response) {
            throw new HttpResponseException();
        }
        $maxDepthHandler = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {};
        $defaultContext = [
            AbstractObjectNormalizer::MAX_DEPTH_HANDLER => $maxDepthHandler,
        ];
        $data = json_decode($response);
        $normalizer = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer]);

        // normalizing properties/attributes. Data model convention name : snake_case as per the output. Return and array
        $result = $serializer->normalize($data, null, [ AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true ]);
        // turning to object to access normalized properties TODO: improve Later : refactory
        $arrayToString = json_encode($result); // TODO REMOVE TO AVOID PERF ISSUES
        $object = json_decode($arrayToString); // TODO MAP THE OBJECT ONLY

        // INITIATE TOPOLOGY - TOPOLOGY DATA FROM RESPONSE
        $topologyData = $object->topology;

        // MAPPING ClusterTopology with response data
        $topology = new ClusterTopology();
        $topology->setEtag($topologyData->etag);
        $topology->setMembers($topologyData->members);
        $topology->setLastNodeId($topologyData->last_node_id);
        $topology->setPromotables($topologyData->promotables);
        $topology->setTopologyId($topologyData->topology_id);
        $topology->setWatchers($topologyData->watchers);
        $topology->setAllNodes($topologyData->all_nodes);

        // STATUS INFO MAPPING TODO : CHECK WITH MARCIN IF IT SHOULD BE GENERATED IN CASE OF NOT NULL
        //
        $status = new NodeStatus();

        // BUILDING THE RESPONSE ClusterTopologyResponse
        $clusterTopologyResponse = new ClusterTopologyResponse();
        $clusterTopologyResponse->setLeader($object->leader);
        $clusterTopologyResponse->setTopology($topology);
        $clusterTopologyResponse->setEtag($object->etag);
        $clusterTopologyResponse->setStatus($status);
        $clusterTopologyResponse->setNodeTag($object->node_tag);
        $clusterTopologyResponse->setTopologyResponse($arrayToString);
        dd($clusterTopologyResponse->getTopologyResponse());
        /// TODO : check with Marcin if it can be with return statment. Original being void
        return $this->result = $clusterTopologyResponse;
    }
    public function isReadRequest(): bool
    {
        return true;
    }
}
/*TODO SOURCE
public class GetClusterTopologyCommand extends RavenCommand<ClusterTopologyResponse> {

    private final String _debugTag;

    public GetClusterTopologyCommand() {
        super(ClusterTopologyResponse.class);

        _debugTag = null;
    }

    public GetClusterTopologyCommand(String debugTag) {
        super(ClusterTopologyResponse.class);
        _debugTag = debugTag;
    }

    @Override
    public HttpRequestBase createRequest(ServerNode node, Reference<String> url) {
        url.value = node.getUrl() + "/cluster/topology";

        if (_debugTag != null)
            url.value += "?" + _debugTag;

        return new HttpGet();
    }

    @Override
    public void setResponse(String response, boolean fromCache) throws IOException {
        if (response == null) {
            throwInvalidResponse();
        }

        result = mapper.readValue(response, resultClass);
    }

    @Override
    public boolean isReadRequest() {
        return true;
    }
}

*/