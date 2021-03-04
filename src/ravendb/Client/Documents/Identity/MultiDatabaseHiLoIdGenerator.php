<?php


namespace RavenDB\Client\Documents\Identity;


use RavenDB\Client\Documents\DocumentStore;

class MultiDatabaseHiLoIdGenerator
{
    private DocumentStore $store;
    //private final ConcurrentMap<String, MultiTypeHiLoIdGenerator> _generators = new ConcurrentHashMap<>();
    public function __construct(DocumentStore $store)
    {
        $this->store = $store;
    }
    public function  generateDocumentId(string $database, object $entity) : string {
        $database = $this->store->getEffectiveDatabase($database);
        //MultiTypeHiLoIdGenerator generator = _generators.computeIfAbsent(database, x -> generateMultiTypeHiLoFunc(x));
       // return generator.generateDocumentId(entity);
    }

    public function generateMultiTypeHiLoFunc(String $database):MultiTypeHiLoIdGenerator {
        return new MultiTypeHiLoIdGenerator($this->store, $database);
    }
}