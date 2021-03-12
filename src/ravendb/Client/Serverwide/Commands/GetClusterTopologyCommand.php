<?php
namespace RavenDB\Client\Serverwide\Commands;

use HttpResponseException;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;

class GetClusterTopologyCommand extends RavenCommand
{
    private null|string $_debugTag;

    public function __construct(?string $debugTag = null)
    {
        //TODO super(ClusterTopologyResponse.class);
        $this->_debugTag = $debugTag === null ? null : $debugTag;
    }

    /*  TODO : createRequest IS NOT EXPECTED TO SEND THE REQUEST : REQUEST SENDER TO DO WITHOUT curl_close AS IT IS INTERNALLY MANAGED
        $curlUrl = curl_init();
        curl_setopt_array($curlUrl, $requestOptions);
        $result = curl_exec($curlUrl);
        return $result;
    */
    public function createRequest(ServerNode $node, string &$url): string
    {
        $url = $node->getUrl() . "/cluster/topology";
        if ($this->_debugTag !== null) $url .= "?" . $this->_debugTag;
        $requestOptions = [
            CURLOPT_URL => $url,
            CURLOPT_PORT => 9095,
            CURLOPT_RETURNTRANSFER => true
            /* TODO: CONFIRM WITH MARCIN WHICH OPTIONS TO PRESERV
            CURLOPT_VERBOSE,
            CURLOPT_HEADER,
            CURLOPT_SSLVERSION,
            CURLOPT_SSLCERT,
            CURLOPT_SSLKEY,
            CURLOPT_CAINFO,
            CURLOPT_POST,
            CURLOPT_SSL_VERIFYPEER,
            CURLOPT_HTTPHEADER */
        ];
        return json_encode($requestOptions);
    }

    public function setResponse(string $response, bool $fromCache)
    {
        // TODO : THROWING A REGULAR EXCEPTION
        if (null === $response) {
            throw new HttpResponseException();
        }
        // TODO: ORIGINAL result = mapper.readValue(response, resultClass);
        $this->result = $response;
    }

    public function isReadRequest(): bool
    {
        return true;
    }
}