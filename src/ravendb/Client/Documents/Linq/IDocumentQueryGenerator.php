<?php

namespace RavenDB\Client\Documents\Linq;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

/**
 * Generate a new document query
 */
interface IDocumentQueryGenerator
{
    /**
     * Gets the conventions associated with this query. Return document conventions
     */
    public function getConventions():DocumentConventions;
    public function getSession():InMemoryDocumentSessionOperations;

    /**
     * Create a new Document query
     */
    public function documentQuery():IDocumentQuery;
}
