<?php

namespace RavenDB\Client\Documents\Session;

use Ramsey\Uuid\Uuid;
use RavenDB\Client\Documents\DocumentStore;

class DocumentSession extends InMemoryDocumentSessionOperations
{
    private DocumentStore $documentStore;
    private string $id;
    private SessionOptions $options;
    public function __construct(DocumentStore $documentStore, string $id, SessionOptions $options)
    {
        $this->documentStore = $documentStore;
        $this->id = $id;
        $this->options = $options;
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}
