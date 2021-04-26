<?php

namespace RavenDB\Client\Documents\Session;
use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Documents\Batches\ICommandData;
use RavenDB\Client\Documents\Commands\Batches\BatchOptions;

class SaveChangesData
{
    private $sessionCommands;
    private ?ArrayCollection $entities=null;
    private ?BatchOptions $options;

    public function __construct(InMemoryDocumentSessionOperations $session)
    {
        $this->options = $session->_saveChangesOptions;
    }

    public function getSessionCommands(): ?array
    {
        return $this->sessionCommands;
    }

    public function getEntities(): ?ArrayCollection
    {
        return $this->entities;
    }

    public function getOptions(): ?BatchOptions
    {
        return $this->options;
    }
}
