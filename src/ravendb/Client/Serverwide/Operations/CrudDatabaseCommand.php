<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Data\Driver\RavenDB;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Util\ObjectMapper;

class CrudDatabaseCommand extends RavenCommand
{
    use ObjectMapper;
    private string $database;
    private string $body;
    public function __construct(string $database,string $body)
    {
        $this->database=$database;
        $this->body = $body;
    }

    public function isReadRequest(): bool
    {
        return true;
    }

    /**
     * @throws \Exception
     */
    public function createRequest(ServerNode $node): array|string|object
    {
        $url = $node->getUrl()."/databases/".$this->database."/bulk_docs";
        $body = $this->serialize($this->entity);
        dd($body);
        $httpClient = new RavenDB();
        $curlopt = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST=>"2",
            CURLOPT_SSL_VERIFYPEER=>"1",
            CURLOPT_CUSTOMREQUEST=>"PUT",
            CURLOPT_POSTFIELDS=>$body
        ];
        return $httpClient->createCurlRequest($url,$curlopt);
    }

    public function setResponse(array|string $response, bool $fromCache)
    {
        parent::setResponse($response, $fromCache);
    }
}