<?php

namespace RavenDB\Client\Documents\Session;
use RavenDB\Client\Documents\Batches\ICommandData;
use RavenDB\Client\Documents\Commands\Batches\BatchOptions;

class SaveChangesData
{
    private array $sessionCommands;
    private array|null $entities=null;
    private ?BatchOptions $options;

    public function __construct(InMemoryDocumentSessionOperations $session)
    {
        $this->options = $session->_saveChangesOptions;
    }

    public function getSessionCommands(): array|null
    {
        return $this->sessionCommands=["Command here"];
    }

    public function getEntities(): array|null
    {
        return $this->entities;
    }

    public function getOptions(): ?BatchOptions
    {
        return $this->options;
    }
}
