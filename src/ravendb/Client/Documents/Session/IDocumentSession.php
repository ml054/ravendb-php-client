<?php

namespace RavenDB\Client\Documents\Session;

use RavenDB\Client\Documents\Session\Loaders\ILoaderWithInclude;
use RavenDB\Client\Primitives\Closable;

interface IDocumentSession extends Closable
{
    /**
     * Marks the specified entity for deletion. The entity will be deleted when DocumentSession.saveChanges is called.
     * WARNING: This method will not call beforeDelete listener!
     * @param string $id
     * @param string $expectedChangeVector
     */

    public function delete(string $id, string $expectedChangeVector);

    /**
     * Saves all the pending changes to the server.
     */
    public function saveChanges();

    /**
     * Stores entity in session with given id and forces concurrency check with given change-vector.
     * @param object $entity
     * @param string $changeVector
     * @param string $id
     */
    public function store(string|object $entity, string $id,?string $changeVector=null,string $forceConcurrencyCheck=null);

    /**
     * Begin a load while including the specified path
     * Path in documents in which server should look for a 'referenced' documents.
     * @param string $path
     * @return ILoaderWithInclude with includes
     */
    public function include(string $path): ILoaderWithInclude;

    /**
     *  Loads the specified entity with the specified id.
     */
    public function load(string $clazz, string $id);

    public function advanced():IAdvancedSessionOperations;
}
