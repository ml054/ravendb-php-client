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
    private int $replicationFactor;
    private string $etag;
    private string $databaseName;

    public function getRaftUniqueRequestId(): string
    {
        // TODO: Implement getRaftUniqueRequestId() method.
    }

    public function isReadRequest(): bool
    {
        // TODO: Implement isReadRequest() method.
    }

    public function createRequest(ServerNode $node, &$url)
    {
        // TODO: Implement createRequest() method.
    }

    public function __construct(DocumentConventions $conventions, DatabaseRecord $databaseRecord, int $replicationFactor, ?string $etag=null) {

        /* TODO: IMPORT
         *  super(DatabasePutResult.class);
            this.conventions = conventions;
            this.databaseRecord = databaseRecord;
            this.replicationFactor = replicationFactor;
            this.etag = etag;
            this.databaseName = Optional.ofNullable(databaseRecord).map(x -> x.getDatabaseName()).orElseThrow(() -> new IllegalArgumentException("Database name is required"));
         * */
        return new self($conventions, $databaseRecord, $replicationFactor, null);
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
        public boolean isReadRequest() {
            return false;
        }

        @Override
        public String getRaftUniqueRequestId() {
            return RaftIdGenerator.newId();
        }
    }

 * */