<?php

namespace RavenDB\Client\Documents\Commands;

use RavenDB\Client\Data\Driver\RavenDB;
use RavenDB\Client\DataBind\Node\ObjectNode;
use RavenDB\Client\Documents\Batches\PutResult;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;

class PutDocumentCommand extends RavenCommand
{
    private $_id;
    private string $changeVectore;
    private object $_document;

    public function __construct(string $id, string $changeVectore, ObjectNode $document)
    {
        if(null === $id) throw new \InvalidArgumentException("Id cannot be null");
        if(null === $document) throw new \InvalidArgumentException("Document cannot be null");
        $this->_id = $id;
        $this->changeVectore = $changeVectore;
        $this->_document = $document;
    }

    public function isReadRequest(): bool
    {
       return false;
    }

    public function createRequest(ServerNode $node): array|string|object
    {
        $url = $node->getUrl()."/databases/".$node->getDatabase()."/docs?id=".urlencode($this->_id);
        $httpClient = new RavenDB();
        $curlopt = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ];
        return $httpClient->createCurlRequest($url,$curlopt);
    }

    public function setResponse(array|string $response, bool $fromCache)
    {
        if(null === $response) throw new \Exception("Response cannot be null");
        return $this->result = $this->mapper()::readValue($response,PutResult::class);
    }
}
