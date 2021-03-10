<?php

namespace RavenDB\Client\Serverwide\Operations;

use Ramsey\Uuid\Uuid;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Http\VoidRavenCommand;
use RavenDB\Client\Serverwide\DatabaseRecord;

class CreateDatabaseOperation extends RavenCommand implements IServerOperation
{
    private DatabaseRecord $databaseRecord;
    private ?int $replicationFactor;
    private DocumentConventions $conventions;
    private string $etag;
    private string $databaseName;

    public function __construct(DatabaseRecord $databaseRecord,int $replicationFactor)
    {
        $this->databaseRecord = $databaseRecord;
        $this->replicationFactor = $replicationFactor;
    }

    public function getCommand(DocumentConventions $conventions): RavenCommand|VoidRavenCommand
    {
        return new CreateDatabaseCommand($conventions, $this->databaseRecord, $this->replicationFactor,null);
    }

    public function isReadRequest(): bool
    {
        return false;
    }

    public function createRequest(ServerNode $node, &$url)
    {
        /*
         * url.value = node.getUrl() + "/admin/databases?name=" + databaseName;

            url.value += "&replicationFactor=" + replicationFactor;

            try {
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
         * */
    }

    public function getRaftUniqueRequestId(): string
    {
       return Uuid::uuid4()->toString();
    }
}
