<?php

namespace RavenDB\Client\Documents\Session;

use RavenDB\Client\Documents\Commands\Batches\BatchOptions;

class SaveChangesData
{
    private ?array $sessionCommands=null;
    private ?array $entities=null;
    private ?BatchOptions $options=null;

    public function __construct(InMemoryDocumentSessionOperations $session)
    {
        $this->options = $session->_saveChangesOptions;
    }

    public function getSessionCommands(): array
    {
        return $this->sessionCommands;
    }

    public function getEntities(): array
    {
        return $this->entities;
    }

    public function getOptions(): BatchOptions
    {
        return $this->options;
    }
}
