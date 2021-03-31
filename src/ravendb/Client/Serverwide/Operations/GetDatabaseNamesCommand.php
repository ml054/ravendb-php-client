<?php

namespace RavenDB\Client\Serverwide\Operations;

use Exception;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;

class GetDatabaseNamesCommand extends RavenCommand
{
    private int $_start;
    private int $_pageSize;

    public function __construct(int $_start, int $_pageSize)
    {
        $this->_start = $_start;
        $this->_pageSize = $_pageSize;
    }

    public function isReadRequest(): bool
    {
        return true;
    }

    public function createRequest(ServerNode $node): array
    {
        $url = $node->getUrl() ."/databases?start=".$this->_start."&pageSize=".$this->_pageSize."&namesOnly=true";
        return [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ];
    }

    /**
     * @param string|array $response
     * @param bool $fromCache
     * @return void
     */
    public function setResponse(string|array $response, bool $fromCache):void
    {
        if (null === $response) {
            self::throwInvalidResponse(null);
            return;
        }

        $jsonObject = json_decode($response);
        if(!property_exists($jsonObject,'Databases')){
            self::throwInvalidResponse(null);
        }
        $databases = $jsonObject->Databases;
        if(!is_array($databases)){
            self::throwInvalidResponse();
        }

        $databaseNames = [] ;
        $dbNames = $databases;
        for( $i=0; $i< count($databases); $i++){
            $databaseNames[$i] = $dbNames[$i];
        }
        $this->result = $databaseNames;
    }
}
