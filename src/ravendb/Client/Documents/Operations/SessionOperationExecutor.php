<?php

namespace RavenDB\Client\Documents\Operations;

use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;
use RavenDB\Client\Exceptions\IllegalStateException;

class SessionOperationExecutor extends OperationExecutor
{
    private InMemoryDocumentSessionOperations $_session;
    public function __construct(InMemoryDocumentSessionOperations $session)
    {
        parent::__construct($session->getDocumentStore(),$session->getDatabaseName());
        $this->_session = $session;
    }

    public function forDatabase(string $databaseName): OperationExecutor
    {
        throw new IllegalStateException("The method is not supported");
    }
}
