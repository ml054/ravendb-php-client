<?php
namespace RavenDB\Client\Serverwide\Commands;
use RavenDB\Client\Data\Driver\RavenDB;
use RavenDB\Client\Http\ClusterTopologyResponse;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Methods\HttpRequestBase;

class GetClusterTopologyCommand extends RavenCommand
{
    private null|string $_debugTag;

    public function __construct(?string $debugTag = null)
    {
        $this->_debugTag = $debugTag !== null ? $debugTag : null;
    }

    /**
     * @throws \Exception
     */
    public function createRequest(ServerNode $node): \CurlHandle
    {
        $url = $node->getUrl() . "/cluster/topology";
        if ($this->_debugTag !== null) $url .= "?" . $this->_debugTag;
        $httpClient = new HttpRequestBase();
        $curlopt = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ];
        return $httpClient->createCurlRequest($url,$curlopt);
    }

    public function setResponse(string|array $response, bool $fromCache): ClusterTopologyResponse
    {
        return $this->result = $this->mapper()::readValue($response,ClusterTopologyResponse::class);
    }

    public function isReadRequest(): bool
    {
        return true;
    }
}