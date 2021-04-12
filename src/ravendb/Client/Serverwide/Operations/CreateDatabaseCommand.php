<?php


namespace RavenDB\Client\Serverwide\Operations;


use CurlHandle;
use Exception;
use RavenDB\Client\Constants;
use RavenDB\Client\Data\Driver\RavenDB;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Http\IRaftCommand;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Serverwide\DatabaseRecord;
use RavenDB\Client\Util\RaftIdGenerator;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class CreateDatabaseCommand extends RavenCommand implements IRaftCommand
{
    private DocumentConventions $conventions;
    private DatabaseRecord $databaseRecord;
    private ?int $replicationFactor=null;
    private ?string $etag=null;
    private ?string $databaseName;

    /**
     * DatabaseRecord is providing the database name to be created as injected in the test call
     * @param DocumentConventions $conventions
     * @param DatabaseRecord $databaseRecord
     * @param int $replicationFactor
     */
    public function __construct(DocumentConventions $conventions,DatabaseRecord $databaseRecord,int $replicationFactor){
        $this->conventions = $conventions;
        $this->databaseRecord = $databaseRecord;
        $this->replicationFactor = $replicationFactor;
        $this->databaseName = $databaseRecord->getDatabaseName();
    }

    public function getRaftUniqueRequestId(): string
    {
        return RaftIdGenerator::newId();
    }

    public function isReadRequest(): bool
    {
        return false;
    }

    /**
     * @param \RavenDB\Client\Http\ServerNode $node
     * @return array|string
     * Goal create a request with
     */
    public function createRequest(ServerNode $node): array|string|CurlHandle
    {
        $url = $node->getUrl()."/admin/databases?name=".$this->databaseRecord->getDatabaseName()."&replicationFactor=".$this->replicationFactor;
        $request = null;
        try{
            $databaseDocument = $this->mapper()::writeValueAsString($this->databaseRecord,$this->databaseName);
            $httpClient = new RavenDB();
            $curlotp = [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST=>"2",
                CURLOPT_SSL_VERIFYPEER=>"1",
                CURLOPT_CUSTOMREQUEST=>"PUT",
                CURLOPT_HTTPHEADER=>["Content-Type:application/json"]
            ];
            $request = $httpClient->createCurlRequest($url,$databaseDocument,$curlotp);
        }catch (Exception $e){
        }
        return $request;
    }

    public function setResponse(array|string $response, bool $fromCache)
    {
       if(null === $response){
           throw new Exception("Response is invalid");
       }
       $this->result = $this->mapper()::readValue($response,DatabaseRecord::class);
    }
}
