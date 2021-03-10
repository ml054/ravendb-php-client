<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Serverwide\DatabaseRecord;

class CreateDatabaseOperation implements IServerOperation
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

    public function getCommand(DocumentConventions $conventions): RavenCommand
    {
        return new CreateDatabaseCommand($conventions, $this->databaseRecord, $this->replicationFactor);
    }
}
/*

public class CreateDatabaseOperation implements IServerOperation<DatabasePutResult> {

    private final DatabaseRecord databaseRecord;
    private final int replicationFactor;

    public CreateDatabaseOperation(DatabaseRecord databaseRecord) {
        this(databaseRecord, 1);
    }

    public CreateDatabaseOperation(DatabaseRecord databaseRecord, int replicationFactor) {
        this.databaseRecord = databaseRecord;
        this.replicationFactor = replicationFactor;
    }

    @Override
    public RavenCommand<DatabasePutResult> getCommand(DocumentConventions conventions) {
        return new CreateDatabaseCommand(conventions, databaseRecord, replicationFactor);
    }

    public static class CreateDatabaseCommand extends RavenCommand<DatabasePutResult> implements IRaftCommand {
        private final DocumentConventions conventions;
        private final DatabaseRecord databaseRecord;
        private final int replicationFactor;
        private final Long etag;
        private final String databaseName;

        public CreateDatabaseCommand(DocumentConventions conventions, DatabaseRecord databaseRecord, int replicationFactor) {
            this(conventions, databaseRecord, replicationFactor, null);
        }

        public CreateDatabaseCommand(DocumentConventions conventions, DatabaseRecord databaseRecord, int replicationFactor, Long etag) {
            super(DatabasePutResult.class);
            this.conventions = conventions;
            this.databaseRecord = databaseRecord;
            this.replicationFactor = replicationFactor;
            this.etag = etag;
            this.databaseName = Optional.ofNullable(databaseRecord).map(x -> x.getDatabaseName()).orElseThrow(() -> new IllegalArgumentException("Database name is required"));
        }

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
}

 * */
