<?php

namespace RavenDB\Client\Documents\Session\Operations;

use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

class BatchOperation
{
    private InMemoryDocumentSessionOperations $_session;

    public function __construct(InMemoryDocumentSessionOperations $_session)
    {
        $this->_session = $_session;
    }

    public function createRequest(){

    }

}
