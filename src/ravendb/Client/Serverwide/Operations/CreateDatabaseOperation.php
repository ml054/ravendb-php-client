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

    public function __construct(DatabaseRecord $databaseRecord,int $replicationFactor = 1)
    {
      $this->databaseRecord = $databaseRecord;
      $this->replicationFactor = $replicationFactor;
    }

    public function getCommand(DocumentConventions $conventions): RavenCommand
    {
        return new CreateDatabaseCommand($conventions, $this->databaseRecord, $this->replicationFactor);
    }
}
