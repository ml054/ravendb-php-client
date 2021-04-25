<?php

namespace RavenDB\Client\Documents\Session;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Documents\Commands\Batches\BatchOptions;
use RavenDB\Client\Extensions\JsonExtensions;

class SaveChangesData
{
    private ?ArrayCollection $sessionCommands=null;
    private ?array $entities=null;
    private ?BatchOptions $options=null;

    public function __construct(InMemoryDocumentSessionOperations $session)
    {
        // dd($session->getInternalDocumentsByEntity()->getValues());
     //   $entity = JsonExtensions::storeSerializer()->normalize($session->getInternalDocumentsByEntity()->getValues(),'json');
      //  dd($session->_saveChangesOptions);
        $this->entities = $this->getEntities();
        $this->options = $session->_saveChangesOptions;
    }

    public function getSessionCommands(): ?ArrayCollection
    {
        return $this->sessionCommands;
    }

    public function getEntities(): ?array
    {
        return $this->entities;
    }

    public function getOptions(): ?BatchOptions
    {
        return $this->options;
    }
}
