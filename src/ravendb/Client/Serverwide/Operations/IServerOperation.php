<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;

interface IServerOperation
{
    public function getCommand(DocumentConventions $conventions): RavenCommand;
}
