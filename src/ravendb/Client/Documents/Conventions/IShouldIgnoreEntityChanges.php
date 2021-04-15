<?php

namespace RavenDB\Client\Documents\Conventions;

use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

interface IShouldIgnoreEntityChanges
{
    public function check(InMemoryDocumentSessionOperations $sessionOperations, object $entity, string $documentId):bool;
}
