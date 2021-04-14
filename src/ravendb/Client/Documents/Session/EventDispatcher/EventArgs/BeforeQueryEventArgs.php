<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher\EventArgs;

use RavenDB\Client\Documents\Session\IDocumentQueryCustomization;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

class BeforeQueryEventArgs
{
    private InMemoryDocumentSessionOperations $session;
    private IDocumentQueryCustomization $queryCustomization;

    public function __construct(InMemoryDocumentSessionOperations $session, IDocumentQueryCustomization $queryCustomization)
    {
        $this->session = $session;
        $this->queryCustomization=$queryCustomization;
    }

    public function getSession(): InMemoryDocumentSessionOperations
    {
        return $this->session;
    }

    public function getQueryCustomization(): IDocumentQueryCustomization
    {
        return $this->queryCustomization;
    }
}
