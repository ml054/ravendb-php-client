<?php

namespace RavenDB\Client\Documents\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\IDocumentStore;
use RavenDB\Client\Http\HttpCache;
use RavenDB\Client\Http\VoidRavenCommand;

interface IVoidOperation extends IOperation
{
    public function getCommand(IDocumentStore $store, DocumentConventions $conventions, HttpCache $cache): VoidRavenCommand;
}