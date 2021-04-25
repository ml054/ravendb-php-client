<?php

namespace RavenDB\Client\Documents\Commands;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Methods\HttpRequestBase;

class GetDocumentsCommand extends RavenCommand
{
    private string $_id;
    private ?array $_includes=null;
    private ?bool $_metadataOnly;
    private ?int $_start;
    private ?int $_pageSize;

    public function __construct(?int $start,?int $pageSize)
    {
        parent::__construct([]);
        /*if(null === $id){
            throw new \InvalidArgumentException('id cannot be null');
        }*/
        $this->_start = $start;
        $this->_pageSize = $pageSize;
    }

    public function isReadRequest(): bool
    {
       return true;
    }

    public function createRequest(ServerNode $node): \CurlHandle
    {
        $command = new GetDocumentsCommand(0,10);
        $url = $node->getUrl()."/databases/".$node->getDatabase()."/docs?start=0&pageSize=20";
        $serializer = JsonExtensions::storeSerializer();
        $body = $serializer->serialize($command,'json');
        $httpClient = new HttpRequestBase();
        $curlopt = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST=>"2",
            CURLOPT_SSL_VERIFYPEER=>"1",
            CURLOPT_CUSTOMREQUEST=>"GET",
            CURLOPT_POSTFIELDS=>$body,
            CURLOPT_HEADEROPT=>[
                "json/application"
            ]
        ];
        //dd($curlopt);
        return $httpClient->createCurlRequest($url,$curlopt);
    }

    public function setResponse(array|string $response, bool $fromCache)
    {
        $this->result = "";
    }
}
