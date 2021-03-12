<?php

namespace RavenDB\Client\Documents\Indexes;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\DocumentStore;

interface IAbstractIndexCreationTask
{
    public function getIndexName(): string;

    public function getPriority(): IndexPriority;

    public function getConventions(): DocumentConventions;

    public function setConventions(DocumentConventions $conventions): void;

    public function execute(DocumentStore $store, ?DocumentConventions $conventions = null, ?string $database = null): void;
}