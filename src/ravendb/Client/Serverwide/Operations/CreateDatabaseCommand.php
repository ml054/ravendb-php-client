<?php


namespace RavenDB\Client\Serverwide\Operations;


use Exception;
use RavenDB\Client\Constants;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Http\IRaftCommand;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Serverwide\DatabaseRecord;
use RavenDB\Client\Util\RaftIdGenerator;
use RavenDB\Client\Util\RouteUtils;

class CreateDatabaseCommand extends RavenCommand implements IRaftCommand
{
    private DocumentConventions $conventions;
    private DatabaseRecord $databaseRecord;
    private ?int $replicationFactor=null;
    private ?string $etag=null;
    private string $databaseName;

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
     * @param ServerNode $node
     * @return array
     * @throws Exception
     */
    public function createRequest(ServerNode $node):array
    {
        $url = RouteUtils::node($node,Constants::ROUTE_ADMIN_DATABASE."?", [
                "name"=>$this->databaseName,
                "replicationFactor"=>$this->replicationFactor
        ]);

        try{
            // TODO GENERATE JSON STRING WITH RESPECT TO THE CASING AS PER NODE CONFIGURATION : PascalCase output
            $databaseDocument = $this->mapper()::writeValueAsString($this->databaseRecord);
            dd($databaseDocument);
        }catch (Exception $e){
            throw new Exception();
        }
        // TODO : mapper
        /*
         *  try {
                String databaseDocument = mapper.writeValueAsString(databaseRecord);
                HttpPut request = new HttpPut();
                request.setEntity(new StringEntity(databaseDocument, ContentType.APPLICATION_JSON));


                if (etag != null) {
                    request.addHeader(Constants.Headers.ETAG,"\"" + etag + "\"");
                }

                return request;
            } catch (JsonProcessingException e) {
                throw ExceptionsUtils.unwrapException(e);
            }
        }
         * */
        // TODO TRYING THE MANUAL CREATION BASE IMPLEMENTING. INJECTING HEADER
        $headerEtag = "etag";
        $query = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
        ];

         return $query;
    }

    public function setResponse(array|string $response, bool $fromCache)
    {
       if(null === $response){
           throw new Exception("Response is invalid");
       }
       $this->result = $response;
    }
}
