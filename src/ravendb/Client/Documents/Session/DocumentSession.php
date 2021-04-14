<?php

namespace RavenDB\Client\Documents\Session;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Documents\Session\Loaders\ILoaderWithInclude;

class DocumentSession extends InMemoryDocumentSessionOperations implements IDocumentSessionImpl
{
    private DocumentStore $documentStore;
    private string $id;
    private SessionOptions $options;

    public function __construct(DocumentStore $documentStore, string $id, SessionOptions $options)
    {
        $this->documentStore = $documentStore;
        $this->id = $id;
        $this->options = $options;
        parent::__construct($documentStore,$id,$options);
    }
    public function saveChanges(){
        dd("save changes here");
    }
    public function close(){ }

    public function delete(string $id, string $expectedChangeVector)
    {
        // TODO: Implement delete() method.
    }

    public function store(object $entity, string $changeVector, string $id): void
    {
        // TODO: Implement store() method.
    }

    public function include(string $path): ILoaderWithInclude
    {
        // TODO: Implement include() method.
    }

    public function getConventions(): DocumentConventions
    {
        // TODO: Implement getConventions() method.
    }
}
