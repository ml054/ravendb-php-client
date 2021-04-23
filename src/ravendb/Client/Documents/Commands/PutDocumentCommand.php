<?php

namespace RavenDB\Client\Documents\Commands;

use RavenDB\Client\Data\Driver\RavenDB;
use RavenDB\Client\DataBind\Node\ObjectNode;
use RavenDB\Client\Documents\Batches\PutResult;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Methods\HttpRequestBase;
use RavenDB\Client\Util\ObjectMapper;

class PutDocumentCommand extends RavenCommand
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
        $url = $node->getUrl()."/databases/".$node->getDatabase()."/bulk_docs";
        $httpClient = new HttpRequestBase();
      //  dd($node->getDatabase());
        $serialize = $this->serialize($this->_document);
       // dd($serialize);
        $curlopt = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST=>"2",
            CURLOPT_SSL_VERIFYPEER=>"1",
          //  CURLOPT_CUSTOMREQUEST=>"PUT",
            CURLOPT_POSTFIELDS=>$serialize
        ];
        //dd($curlopt);
        return $httpClient->createCurlRequest($url,$curlopt);
    }

    public function setResponse(array|string $response, bool $fromCache)
    {
        if(null === $response) throw new \Exception("Response cannot be null");
        return $this->result = $this->mapper()::readValue($response,PutResult::class);
    }
}
