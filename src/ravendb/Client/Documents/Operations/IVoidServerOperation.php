<?php

namespace RavenDB\Client\Documents\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\VoidRavenCommand;
use RavenDB\Client\Serverwide\Operations\IServerOperation;

interface IVoidServerOperation extends IServerOperation
{
    public function getCommand(DocumentConventions $conventions):VoidRavenCommand;
}