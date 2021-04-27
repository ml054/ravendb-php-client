<?php

namespace RavenDB\Client\Documents\Commands;

use RavenDB\Client\Documents\Batches\PutResult;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Methods\HttpRequestBase;
use RavenDB\Client\Util\ObjectMapper;

class PutDocumentUpdateCommand extends RavenCommand
{
    use ObjectMapper;
    private $_id;
    private ?string $changeVectore=null;
    private object $_document;

    public function __construct(string $id, object $document, ?string $changeVectore=null)
    {
        if(null === $id) throw new \InvalidArgumentException("Id cannot be null");
        if(null === $document) throw new \InvalidArgumentException("Document cannot be null");
        $this->_id = $id;
        $this->changeVectore = $changeVectore;
        $this->_document = $document;
        parent::__construct(null);
    }

    public function isReadRequest(): bool
    {
       return false;
    }

    /**
     * @throws \Exception
     */
    public function createRequest(ServerNode $node): array|string|object
    {
        $url = $node->getUrl()."/databases/".$node->getDatabase()."/docs?id=".$this->_id;
        $httpClient = new HttpRequestBase();
        $serializer = JsonExtensions::storeSerializer();
        $serialize = $serializer->serialize($this->_document,'json');

        $curlopt = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST=>"2",
            CURLOPT_SSL_VERIFYPEER=>"1",
            CURLOPT_CUSTOMREQUEST=>"PUT",
            CURLOPT_POSTFIELDS=>$serialize,
            CURLOPT_HTTPHEADER=>[
                "application/json",
                // TODO CHECK WITH TECH IF-MATCH HEADER SHOULD BE IMPLEMENTED
            ]
        ];
        return $httpClient->createCurlRequest($url,$curlopt);
    }

    public function setResponse(array|string $response, bool $fromCache):PutResult
    {
       return $this->result = $this->mapper()::readValue($response,PutResult::class);
    }

}
