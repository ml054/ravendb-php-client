<?php

namespace RavenDB\Client\Documents\Session;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Documents\Batches\ICommandData;
use RavenDB\Client\Documents\IDocumentStore;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Http\ServerNode;

/**
Advanced session operations
 */
interface IAdvancedDocumentSessionOperations
{
    public function getDocumentStore():IDocumentStore|ArrayCollection;
    /**
     * Allow extensions to provide additional state per session
     */
    public function getExternalState():IDocumentStore|ArrayCollection;
    public function getCurrentSessionNode():ServerNode;
    public function getRequestExecutor():RequestExecutor;
    public function getSessionInfo():SessionInfo;

    /**
     * Gets a value indicating whether any of the entities tracked by the session has changes.
     * @return true if any entity associated with session has changes
     */
    public function hasChanges(object $entity):bool;

    /**
     * Gets the max number of requests per session.
     * @return int number of requests per session
     */
    public function getMaxNumberOfRequestsPerSession():int;

    /**
     * Sets the max number of requests per session.
     * @param int $maxRequests
     */
    public function setMaxNumberOfRequestsPerSession(int $maxRequests):void;

    /**
     * Gets the number of requests for this session
     * @return int of requests issued on this session
     */
    public function getNumberOfRequests():int;

    /**
     * Gets the store identifier for this session.
     * The store identifier is the identifier for the particular RavenDB instance.
     * @return string store identifier
     */
    public function storeIdentifier():string;

    /**
     * Gets value indicating whether the session should use optimistic concurrency.
     * When set to true, a check is made so that a change made behind the session back would fail
     * and raise ConcurrencyException
     * @return true if optimistic concurrency should be used
     */

    public function isUseOptimisticConcurrency():bool;

    /**
     * Sets value indicating whether the session should use optimistic concurrency.
     * When set to true, a check is made so that a change made behind the session back would fail
     * and raise ConcurrencyException
     * @param bool $useOptimisticConcurrency
     */
    public function setUseOptimisticConcurrency(bool $useOptimisticConcurrency);

    /**
     * Clears this instance.
     * Remove all entities from the delete queue and stops tracking changes for all entities.
     */
    public function clear():void;

    /**
     * Defer commands to be executed on saveChanges()
     * @param \RavenDB\Client\Documents\Batches\ICommandData $command
     * @param \RavenDB\Client\Documents\Batches\ICommandData ...$commands
     */
    public function defer(ICommandData $command, ICommandData ...$commands):void;

    /**
     * Evicts the specified entity from the session.
     * Remove the entity from the delete queue and stops tracking changes for this entity.
     * @param $entity
     */
    public function evict($entity):void;

    /**
     * Gets the document id for the specified entity.
     *
     *  This function may return null if the entity isn't tracked by the session, or if the entity is
     *   a new entity with an ID that should be generated on the server.
     * @param object $entity //Entity to get id from return entity id
     * @return  $entity
     */
    public function getDocumentId(object $entity): ?string;

    /**
     * Gets the metadata for the specified entity.
     * If the entity is transient, it will load the metadata from the store
     * and associate the current state of the entity with the metadata from the server.
     * @param instance instance to get metadata from
     * @return Entity metadata
     */
    public function  getMetadataFor($instance):IMetadataDictionary;

    /**
     * Gets change vector for the specified entity.
     * If the entity is transient, it will load the metadata from the store
     * and associate the current state of the entity with the metadata from the server.
     * @param Instance $instance  to get metadata from
     */
    public function getChangeVectorFor(string $instance):string;

    /**
     * Gets all the counter names for the specified entity.
     * @param string $instance The instance
     * @return array of counter names
     */
    public function getCountersFor(string $instance):array;

    /**
     * Gets all time series names for the specified entity.
     * @param instance The instance
     * @param <T> Class of instance
     * @return List of time series names
     */
    public function getTimeSeriesFor($instance):array;

    /**
     * Gets last modified date for the specified entity.
     * If the entity is transient, it will load the metadata from the store
     * and associate the current state of the entity with the metadata from the server.
     * @param string $instance to get last modified date from
     * @return DateTimeImmutable modified date
     */
    public function getLastModifiedFor(string $instance): DateTimeImmutable;

    /**
     * Determines whether the specified entity has changed.
     * @param entity Entity to check
     * @return true if entity has changed
     */
    public function hasChanged(object $entity):bool;

    /**
     * Returns whether a document with the specified id is loaded in the
     * current session
     * @param id Id of document
     * @return true is entity is loaded in session
     */
    public function isLoaded(string $id):bool;

    /**
     * Mark the entity as one that should be ignore for change tracking purposes,
     * it still takes part in the session, but is ignored for SaveChanges.
     * @param entity Entity for which changed should be ignored
     */
    public function ignoreChangesFor(object $entity):void;

    /**
     * Returns all changes for each entity stored within session. Including name of the field/property that changed, its old and new value and change type.
     */
    public function whatChanged():DocumentsChanges;

    /**
     * SaveChanges will wait for the changes made to be replicates to `replicas` nodes
     */
    public function waitForReplicationAfterSaveChanges():void;

}
