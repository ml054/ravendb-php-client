<?php

namespace RavenDB\Client\Documents\Batches;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

interface ICommandData
{
    public function getId():string;
    public function getName():string;
    public function getChangeVector():string;
}
