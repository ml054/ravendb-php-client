<?php


namespace RavenDB\Client\Serverwide\Operations;


use Exception;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\IRaftCommand;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Serverwide\DatabaseRecord;

class CreateDatabaseCommand extends RavenCommand implements IRaftCommand
{
    private DocumentConventions $conventions;
    private DatabaseRecord $databaseRecord;
    private ?int $replicationFactor=null;
    private ?string $etag=null;
    private ?string $databaseName=null;

    public function __construct(DocumentConventions $conventions,DatabaseRecord $databaseRecord,int $replicationFactor){
        $this->conventions = $conventions;
        $this->databaseRecord = $databaseRecord;
        $this->replicationFactor = $replicationFactor;
        // HARD CODING DATABASE NAME
        $this->databaseName="dbname_create_1";
    }

    public function getRaftUniqueRequestId(): string
    {
        // TODO: Implement getRaftUniqueRequestId() method.
    }

    public function isReadRequest(): bool
    {
        return false;
    }

    public function createRequest(ServerNode $node):array
    {
        $url = $node->getUrl()."/admin/databases?name=".$this->databaseName;
        $url .= "&replicationFactor=".$this->replicationFactor;
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
        return [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ];
    }

    public function setResponse(array|string $response, bool $fromCache)
    {
       if(null === $response){
           throw new Exception("Response is invalid");
       }

       dd($response);
    }
}
