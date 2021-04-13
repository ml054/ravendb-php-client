<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Util\Duration;

class DeleteDatabasesOperation implements IServerOperation
{
    private Parameters $parameters;

    public function __construct(string $databaseName, bool $hardDelete, string|null $fromNode, int|null $timeToWaitForConfirmation)
    {
        if(null === $databaseName){
            throw new \InvalidArgumentException("Database name cannot be null");
        }
        $parameters = new Parameters();
        $parameters->setDatabaseNames($databaseName);
        $parameters->setHardDelete($hardDelete);
        $parameters->setFromNodes($fromNode);
        $parameters->setTimeToWaitForConfirmation($timeToWaitForConfirmation);

        if(null !== $fromNode){
            $parameters->setFromNodes($fromNode);
        }
        $this->parameters = $parameters;
    }

    /**
     * @throws \Exception
     */
    public function getCommand(DocumentConventions $conventions): RavenCommand
    {
        return new DeleteDatabaseCommand($conventions,$this->parameters);
    }
}
