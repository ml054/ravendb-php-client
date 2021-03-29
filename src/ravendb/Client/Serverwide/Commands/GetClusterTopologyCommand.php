<?php
namespace RavenDB\Client\Serverwide\Commands;
use RavenDB\Client\Http\ClusterTopologyResponse;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;

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
    public function setResponse(string|array $response, bool $fromCache): ClusterTopologyResponse
    {
        $jsonResponse = json_decode($response);
        $topologyResponse = new ClusterTopologyResponse($jsonResponse);
        return $this->result = $topologyResponse->response();
    }


    public function isReadRequest(): bool
    {
        return true;
    }
}