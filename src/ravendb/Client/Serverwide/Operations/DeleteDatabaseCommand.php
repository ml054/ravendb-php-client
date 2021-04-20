<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Data\Driver\RavenDB;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;

class DeleteDatabaseCommand extends RavenCommand
{
    private string $parameters;

    /**
     * @throws \Exception
     */
    public function __construct(DocumentConventions $conventions, Parameters $parameters)
    {
        if ( null === $conventions ) throw new \InvalidArgumentException("Conventions cannot be null");
        if ( null === $parameters ) throw new \InvalidArgumentException("Parameters cannot be null");

        try{
            $this->parameters = $this->mapper()::writeValueAsString($parameters);
        }catch (\Exception $e){
            throw new \Exception('JsonProcessingException');
        }
    }

    /**
     * @throws \Exception
     */
    public function createRequest(ServerNode $node): array|string|object
    {
        $url = $node->getUrl()."/admin/databases";
        $httpClient = new RavenDB();
        $curlopt = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST=>"2",
            CURLOPT_SSL_VERIFYPEER=>"1",
            CURLOPT_CUSTOMREQUEST=>"DELETE",
            CURLOPT_POSTFIELDS=>$this->parameters
        ];
        dd($curlopt);
        $request = $httpClient->createCurlRequest($url,$curlopt);

    }

    public function isReadRequest(): bool
    {
        return false;
    }
}
