<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher\EventArgs;

use RavenDB\Client\Documents\Session\IMetadataDictionary;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

/**
 * Interface IInMemoryDocumentSession
 * @package RavenDB\Client\Documents\Session\EventDispatcher\EventArgs
 * Contractor for all the EventArgs mapped in InMemoryDocumentSessionOperations
 */
interface IInMemoryDocumentSessionEvent
{
    public function getDocumentMetadata(): IMetadataDictionary;
    public function getSession(): InMemoryDocumentSessionOperations;
    public function getDocumentId(): string;
    public function getEntity(): object;
}
