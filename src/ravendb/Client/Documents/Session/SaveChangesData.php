<?php

namespace RavenDB\Client\Documents\Session;
use Doctrine\Common\Collections\ArrayCollection;
use Ds\Map;
use RavenDB\Client\Documents\Batches\ICommandData;
use RavenDB\Client\Documents\Commands\Batches\BatchOptions;

class SaveChangesData
{
    private ArrayCollection $sessionCommands;
    private ArrayCollection $entities;
    private ?BatchOptions $options;

    public function __construct(InMemoryDocumentSessionOperations $session)
    {
        $this->options = $session->_saveChangesOptions;
        $this->entities = new ArrayCollection();
        $this->sessionCommands = new ArrayCollection();
    }

    public function getSessionCommands() : ArrayCollection
    {
        return $this->sessionCommands;
    }

    public function getEntities() : ArrayCollection
    {
        return $this->entities;
    }

    public function getOptions(): ?BatchOptions
    {
        return $this->options;
    }
}
