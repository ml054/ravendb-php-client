<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher\EventArgs;

use RavenDB\Client\Documents\Session\IMetadataDictionary;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

class BeforeDeleteEventArgs implements IInMemoryDocumentSessionEvent
{
    private IMetadataDictionary $_documentMetadata;
    private InMemoryDocumentSessionOperations $session;
    private string $documentId;
    private object $entity;

    public function __construct(InMemoryDocumentSessionOperations $session, string $documentId, object $entity)
    {
        $this->session = $session;
        $this->documentId = $documentId;
        $this->entity = $entity;
    }

    public function getDocumentMetadata(): IMetadataDictionary
    {
        if(null === $this->_documentMetadata){
            $this->_documentMetadata = $this->session->getMetadataFor($this->entity);
        }
        return $this->_documentMetadata;
    }

    public function getSession(): InMemoryDocumentSessionOperations
    {
        return $this->session;
    }

    public function getDocumentId(): string
    {
        return $this->documentId;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }
}
