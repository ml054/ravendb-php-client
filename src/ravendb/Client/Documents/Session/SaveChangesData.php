<?php

namespace RavenDB\Client\Documents\Session;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Documents\Commands\Batches\BatchOptions;

class SaveChangesData
{
    private ArrayCollection $sessionCommands;
    private ?array $entities=null;
    private ?BatchOptions $options=null;

    public function __construct(InMemoryDocumentSessionOperations $session)
    {
        dd($session);
        $this->options = $session->_saveChangesOptions;
    }

    public function getSessionCommands(): ArrayCollection
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
