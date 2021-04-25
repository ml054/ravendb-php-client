<?php

namespace RavenDB\Client\Serverwide\Commands;

use Ramsey\Uuid\Uuid;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Http\Topology;
use RavenDB\Client\Methods\HttpRequestBase;
use RavenDB\Client\Util\ObjectMapper;

class GetDatabaseTopologyCommand extends RavenCommand
{
    use ObjectMapper;
    private ?string $_applicationIdentifier=null;
    private ?string $_debugTag=null;

    public function __construct(?string $debugTag, ?Uuid $applicationIdentifier)
    {
        parent::__construct(Topology::class);
        $this->_debugTag = $debugTag;
        $this->canCacheAggressively = false;
        $this->_applicationIdentifier = $applicationIdentifier;
    }

    public function createRequest(ServerNode $node): array|string|object
    {
        $url = $node->getUrl()."/topology?name=".$node->getDatabase();
        $httpClient = new HttpRequestBase();
        $curlopt = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST=>"2",
            CURLOPT_SSL_VERIFYPEER=>"1",
            CURLOPT_CUSTOMREQUEST=>"GET",
            CURLOPT_HTTPHEADER=>[
                "application/json",
            ]
        ];
        $request = $httpClient->createCurlRequest($url,$curlopt);
        return $request;
       // dd($request);
    }

    public function setResponse(array|string $response, bool $fromCache)
    {

       if(null === $response){
           return;
       }

       $this->result = $this->mapper()::readValue($response,$this->resultClass);
    }

    public function isReadRequest(): bool
    {
        return true;
    }
}
