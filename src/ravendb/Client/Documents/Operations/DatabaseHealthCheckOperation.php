<?php


namespace RavenDB\Client\Documents\Operations;


use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;

class DatabaseHealthCheckOperation implements IMaintenanceOperation
{
    public function getCommand(DocumentConventions $conventions): RavenCommand
    {
        // TODO: Implement getCommand() method.
        //
    }
}