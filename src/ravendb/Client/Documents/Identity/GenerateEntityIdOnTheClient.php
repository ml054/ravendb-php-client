<?php

namespace RavenDB\Client\Documents\Identity;

use RavenDB\Client\Documents\Conventions\DocumentConventions;

class GenerateEntityIdOnTheClient
{
    private DocumentConventions $_conventions;
    private object|string $_generateId;

    public function __construct(DocumentConventions $conventions, $generateId)
    {
        $this->_conventions = $conventions;
        $this->_generateId = $generateId;
    }


}