<?php

namespace RavenDB\Client\Data\Driver;

use Exception;
use RavenDB\Client\Constants;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Util\RouteUtils;
// TODO IMPLEMENT MORE METHOD (get, delete). And improve the class
class RavenDB
{
    private mixed $pem;
    private DocumentStore $node;
    private ?string $database;
    private string|object $response;
    function __construct(DocumentStore $node, ?string $database) {
        $this->node = $node;
        $this->database = $database;
    }

    /**
     * @throws \Exception
     */
    function put($id, $body) {
        $url = RouteUtils::store($this->node,"/databases/".$this->database.Constants::ROUTE_DOCS."?", [ "id"=>$id ]);
        $this->_exec("PUT", $url, 201, $body);
    }

    /**
     * @throws \Exception
     * TODO : IMPLEMENT match to replace switch for more accuracy
     */
    private function _exec($method, $url, $expectedStatusCode, $body): void
    {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '2');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '1');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
//            curl_setopt($curl, CURLOPT_SSLCERT, $this->pem);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            if($body != NULL){
                curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
            }
            $response = curl_exec($curl);
            if (!curl_errno($curl)) {
                switch ($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
                    case $expectedStatusCode:
                        $this->response = json_decode($response);
                        return;
                    case 404:
                        return;
                    default:
                        echo $this->response = $response;
                        throw new Exception( $url . " GOT "  . $http_code . " - " . $response);
                }
            }
        }

    /**
     * @return string|null
     */
    public function getPem(): ?string
    {
        return $this->pem;
    }

    /**
     * @param mixed $pem
     */
    public function setPem(?string $pem): void
    {
        $this->pem = $pem;
    }

    /**
     * @return string|object
     */
    public function getResponse(): string|object
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
