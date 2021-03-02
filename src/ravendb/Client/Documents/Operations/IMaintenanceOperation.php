<?php

namespace RavenDB\Client\Documents\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;

interface IMaintenanceOperation
{
    public function getCommand(DocumentConventions $conventions): RavenCommand;
}
