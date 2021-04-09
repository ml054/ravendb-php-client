<?php

namespace RavenDB\Client\Data\Driver;

use Exception;
use RavenDB\Client\Constants;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Util\RouteUtils;

class RavenDB
{
    // TODO BUILD RAVENDB DATA DRIVER TO CONNECT TO A STORE DATABASE TO DO CRUD OPERATION. REQUIRES THE OPERATION CREATEDATABASE IN PLACE. WORK IN PROGRESS
    private $dbName;
    private $pem;
    private ServerNode $node;
    function __construct(ServerNode $node) {
        $this->node = $node;
    }

    /**
     * @throws \Exception
     */
    function put($id, $doc) {
        $url = RouteUtils::node($this->node,Constants::ROUTE_DOCS."?", [
            "id"=>$id,
        ]);
        $body = json_encode($doc);
        return $this->_exec("PUT", $url, 201, $body);
    }

    /**
     * @throws \Exception
     */
    function get($id) {
        $url = $this->_url("/docs?id=" . $id);
        return $this->_exec("GET", $url, 200, NULL)->Results[0];
    }

    function query($query, $args = NULL) {
        $r = $this->raw_query($query, $args);

        return $r->Results;
    }

    /**
     * @throws \Exception
     */
    function raw_query($query, $args = NULL) {
        $url = $this->_url("/queries");
        $body = json_encode(["Query" => $query, "QueryParameters" => $args]);
        return $this->_exec("POST", $url, 200, $body);
    }

    function del($id) {
        $url = $this->_url("/docs?id=" . $id);
        $this->_exec("DELETE", $url, 204, NULL);
    }
    /**
     * @throws
    */
    private function _exec($method, $url, $expectedStatusCode, $body) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '2');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '1');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_SSLCERT, $this->pem);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            if($body != NULL){
                curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
            }
            $response = curl_exec($curl);
            if (!curl_errno($curl)) {
                switch ($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
                    case $expectedStatusCode:
                        return json_decode($response);
                    case 404:
                        return NULL;
                    default:
                        echo $response;
                        throw new Exception( $url . " GOT "  . $http_code . " - " . $response);
                }
            }
        }

    /**
     * @return string
     */
    public function getDbName(): string
    {
        return $this->dbName;
    }

    /**
     * @param string $dbName
     */
    public function setDbName(string $dbName): void
    {
        $this->dbName = $dbName;
    }

    /**
     * @return mixed
     */
    public function getPem()
    {
        return $this->pem;
    }

    /**
     * @param mixed $pem
     */
    public function setPem($pem): void
    {
        $this->pem = $pem;
    }

    /**
     * @return \RavenDB\Client\Http\ServerNode
     */
    public function getNode(): ServerNode
    {
        return $this->node;
    }

    /**
     * @param \RavenDB\Client\Http\ServerNode $node
     */
    public function setNode(ServerNode $node): void
    {
        $this->node = $node;
    }


}
