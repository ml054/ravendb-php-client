<?php

namespace RavenDB\Client\Documents\Session;

use RavenDB\Client\Documents\Conventions\DocumentConventions;

interface IDocumentSessionImpl extends IDocumentSession
{
    public function getConventions():DocumentConventions;
}
