<?php


namespace RavenDB\Client\Documents\Identity;


use RavenDB\Client\Documents\DocumentStore;

class MultiDatabaseHiLoIdGenerator
{
    private DocumentStore $store;
    public function __construct(DocumentStore $store)
    {
        $this->store = $store;
    }
    public function  generateDocumentId(string $database, object $entity) : string {
        // TODO: To complete
        $database = $this->store->getEffectiveDatabase($database);
    }

    public function generateMultiTypeHiLoFunc(String $database):MultiTypeHiLoIdGenerator {
        return new MultiTypeHiLoIdGenerator($this->store, $database);
    }
}
