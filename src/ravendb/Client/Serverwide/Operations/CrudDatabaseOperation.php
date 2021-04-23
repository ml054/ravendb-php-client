<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;

class CrudDatabaseOperation implements IServerOperation
{
    private object $entity;
    private string $database;
    private string $body;
    private string $id;
    private string $command;
    public function __construct(string $command,string $database)
    {
        $this->command = $command;
        $this->database = $database;
    }
    /**
     * @param DocumentConventions|null $conventions
     * @return RavenCommand
     */
    public function getCommand(?DocumentConventions $conventions = null): RavenCommand
    {
        return new CrudDatabaseCommand($this->command,$this->database);
    }
}