<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher\EventArgs;

use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

class BeforeConversionToDocumentEventArgs
{
    private string $_id;
    private object $_entity;
    private InMemoryDocumentSessionOperations $_session;

    public function __construct(InMemoryDocumentSessionOperations $session, string $id, object $entity)
    {
        $this->_session = $session;
        $this->_id = $id;
        $this->_entity = $entity;
    }

    public function getId(): string
    {
        return $this->_id;
    }

    public function getEntity(): object
    {
        return $this->_entity;
    }

    public function getSession(): InMemoryDocumentSessionOperations
    {
        return $this->_session;
    }
}
