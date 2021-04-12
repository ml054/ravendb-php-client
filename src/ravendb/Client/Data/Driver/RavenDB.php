<?php

namespace RavenDB\Client\Data\Driver;

use CurlHandle;
use Exception;
use RavenDB\Client\Constants;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Util\RouteUtils;
class RavenDB
{
    private string|object $response;
    /**
     * @throws Exception
     * Only create the call. no execution to server
     */
    public function createCurlRequest($url,string $body, array $curlopt): bool|CurlHandle
    {
        if(!is_array($curlopt)) throw new Exception('Attempt failed. No array option submitted. Check your configuration');
        $curl = curl_init($url);
        if($body !== null){
            array_push($curlopt,[CURLOPT_POSTFIELDS=>$body]);
        }
        curl_setopt_array($curl, $curlopt);
        return $curl;
    }
    /**
     * @throws Exception
     * Execute the curl call to the server
     */
    public function execute($url, int $expectedStatusCode, string $body, array $curlopt): void
    {
        if(!is_array($curlopt)) throw new Exception('No array option submitted. Check your configuration');
        $curl = curl_init($url);
        if($body !== null){
            array_push($curlopt,[CURLOPT_POSTFIELDS=>$body]);
        }
        curl_setopt_array($curl, $curlopt);
        $this->response = $body;
            $response = curl_exec($curl);
            if (!curl_errno($curl)) {
                switch ($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
                    case $expectedStatusCode:
                        $this->response = json_decode($response);
                        return;
                    default:
                        echo $this->response = $response;
                        throw new Exception( $url . " GOT "  . $http_code . " - " . $response);
                }
            }
    }

    public function getResponse(): string|array
    {
        $serializer = JsonExtensions::serializer();
        return $serializer->decode($this->response,'json');
    }

    /**
     * @param string $response
     */
    public function setResponse(string $response): void
    {
        $this->response = $response;
    }
}
