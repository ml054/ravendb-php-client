<?php


namespace RavenDB\Client\Serverwide\Operations;


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
        /* TODO: IMPORT
       * */
        $this->conventions = $conventions;
        $this->databaseRecord = $databaseRecord;
        $this->replicationFactor = $replicationFactor;
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
         $url = $node->getUrl()."/admin/databases?name".$this->databaseName;
        $url .= "&replicationFactor=".$this->replicationFactor;
        return [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ];
    }
}
/*

        TODO:
        @Override
        public HttpRequestBase createRequest(ServerNode node, Reference<String> url) {
            url.value = node.getUrl() + "/admin/databases?name=" + databaseName;

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
        }

        @Override
        public void setResponse(String response, boolean fromCache) throws IOException {
            if (response == null) {
                throwInvalidResponse();
            }

            result = mapper.readValue(response, DatabasePutResult.class);
        }

        @Override
        public String getRaftUniqueRequestId() {
            return RaftIdGenerator.newId();
        }
    }

 * */