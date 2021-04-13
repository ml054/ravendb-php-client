<?php
namespace RavenDB\Client\Data\Driver;
use CurlHandle;
use Exception;
class RavenDB
{
    private string|object $response;

    private $http_codes = [
        200=>200,
        201=>201,
        202=>202
    ];

    /**
     * @throws Exception
     * Only create the call. no execution to server
     */
    public function createCurlRequest($url,array $curlopt): bool|CurlHandle
    {
        if(!is_array($curlopt)) throw new Exception('Attempt failed. No array option submitted. Check your configuration');
        $curl = curl_init($url);
        curl_setopt_array($curl, $curlopt);
        return $curl;
    }
    /**
     * @throws Exception
     * Execute the curl call to the server
     * NOTE THAT THE CURL_EXEC IS NOT CLOSED. WAS INFORMED AN INTERNAL PROCESS MAY TAKE CARE OF CLOSING THE CONNECTION
     */
    public function execute($curlObject): void
    {
            $response = curl_exec($curlObject);
            if (!curl_errno($curlObject)) {
                $http_code = curl_getinfo($curlObject, CURLINFO_HTTP_CODE);
                $expectedStatusCode = $this->http_codes[$http_code];
                match ($http_code){
                    $expectedStatusCode=> $this->response = $response,
                    default=>$this->response = json_encode(["code"=>$http_code,"message"=>"Unexpected response from server. Consider the response status code for details"]),
                };
            }
    }

    public function getResponse(): string|array
    {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse(string $response): void
    {
        $this->response = $response;
    }
}
