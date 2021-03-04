<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\VoidRavenCommand;

interface IServerOperation
{
    // TODO : VALIDATE WITH MARCIN : ONLY OPTION TO USE VoidRavenCommand along with RavenCommand is pipping both so far
    public function getCommand(DocumentConventions $conventions): RavenCommand|VoidRavenCommand;
}
