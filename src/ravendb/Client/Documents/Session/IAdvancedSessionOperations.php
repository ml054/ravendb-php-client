<?php

namespace RavenDB\Client\Documents\Session;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Documents\IDocumentStore;

interface IAdvancedSessionOperations extends IAdvancedDocumentSessionOperations
{
    public function getDocumentStore():IDocumentStore|ArrayCollection;
    public function getExternalState():IDocumentStore|ArrayCollection;
    public function exists(string $id):bool ;
}
