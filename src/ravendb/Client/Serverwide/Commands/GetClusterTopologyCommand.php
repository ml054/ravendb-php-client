<?php
namespace RavenDB\Client\Serverwide\Commands;
use RavenDB\Client\Extensions\JsonExtensions;
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

    /// TODO MAP THE PROPERTIES AND ADJUST THE RELATED CLASSES. NOT DONE YET
    public function setResponse(string|array $response, bool $fromCache): ClusterTopologyResponse
    {
        $this->result = $this->mapper()::readValue($response,ClusterTopologyResponse::class);
    }

    public function isReadRequest(): bool
    {
        return true;
    }
}