<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;

class CrudDatabaseOperation implements IServerOperation
{
    private object $entity;
    private string $database;
    private string $body;

    public function __construct(object $entity,string $database,string $body)
    {
        $this->entity = $entity;
        $this->database = $database;
        $this->body = $body;
    }
    /**
     * @param DocumentConventions|null $conventions
     * @return RavenCommand
     */
    public function getCommand(?DocumentConventions $conventions = null): RavenCommand
    {
        return new CrudDatabaseCommand($this->entity,$this->database,$this->body);
    }
}