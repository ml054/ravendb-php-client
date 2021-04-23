<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher\EventArgs;

use RavenDB\Client\Documents\Session\IMetadataDictionary;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

class BeforeChangeSetEventArgs
{
    private object $entity;

    public function __construct(object $entity)
    {
        $this->entity = $entity;
    }


    public function getEntity(): object
    {
        return $this->entity;
    }
}
