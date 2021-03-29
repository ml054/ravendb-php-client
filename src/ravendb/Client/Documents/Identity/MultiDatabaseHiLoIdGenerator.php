<?php

namespace RavenDB\Client\Documents\Identity;

use RavenDB\Client\Documents\DocumentStore;

class MultiDatabaseHiLoIdGenerator
{
    protected DocumentStore $store;
    private $_generator;

    public function __construct(DocumentStore $store)
    {
        $this->store = $store;
    }

    public function generateDocumentId(?string $database=null, ?object $entity=null): ?string
    {
        // TODO: To complete
        $database = $this->store->getEffectiveDatabase($database);

    }

    public function generateMultiTypeHiLoFunc(string $database): MultiTypeHiLoIdGenerator
    {
        return new MultiTypeHiLoIdGenerator($this->store, $database);
    }
}
