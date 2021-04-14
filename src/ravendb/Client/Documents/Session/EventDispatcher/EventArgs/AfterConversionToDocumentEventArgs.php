<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher\EventArgs;

use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

class AfterConversionToDocumentEventArgs
{
    private string $_id;
    private object $_entity;
    private object $_document;
    private InMemoryDocumentSessionOperations $_session;

    public function __construct(InMemoryDocumentSessionOperations $session, string $id, object $entity, object $document)
    {
        $this->_session = $session;
        $this->_id = $id;
        $this->_entity = $entity;
        $this->_document = $document;
    }

    public function getId(): string
    {
        return $this->_id;
    }

    public function getEntity(): object
    {
        return $this->_entity;
    }

    public function getDocument(): object
    {
        return $this->_document;
    }

    public function getSession(): InMemoryDocumentSessionOperations
    {
        return $this->_session;
    }
}
