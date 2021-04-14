<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher\EventArgs;

use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

class BeforeConversionToEntityEventArgs
{
    private string $_id;
    private object $_type;
    private object $_document;
    private InMemoryDocumentSessionOperations $_session;

    public function __construct(InMemoryDocumentSessionOperations $session, string $id, object $type, object $document)
    {
        $this->_session = $session;
        $this->_id= $id;
        $this->_type = $type;
        $this->_document = $document;
    }

    public function getId(): string
    {
        return $this->_id;
    }

    public function getType(): object
    {
        return $this->_type;
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
