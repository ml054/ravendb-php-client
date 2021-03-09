<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\VoidRavenCommand;
use RavenDB\Client\Serverwide\DatabaseRecord;

class CreateDatabaseOperation implements IServerOperation
{
    private DatabaseRecord $databaseRecord;
    private ?int $replicationFactor;

    public function __construct(DatabaseRecord $databaseRecord, ?int $replicationFactor = null)
    {
        $this->databaseRecord = $databaseRecord;
        $this->replicationFactor = $replicationFactor;
    }

    public function getCommand(DocumentConventions $conventions): RavenCommand|VoidRavenCommand
    {
        return new CreateDatabaseCommand($conventions, $this->databaseRecord, $this->replicationFactor);
    }
}
