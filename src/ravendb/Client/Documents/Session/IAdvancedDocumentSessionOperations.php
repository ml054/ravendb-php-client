<?php

namespace RavenDB\Client\Documents\Session;

use RavenDB\Client\Documents\IDocumentStore;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Http\ServerNode;

/**
Advanced session operations
 */
interface IAdvancedDocumentSessionOperations
{
    public function getDocumentStore():IDocumentStore;

    /**
     * Allow extensions to provide additional state per session
     * @return External state
     */
    public function getExternalState();

    public function getCurrentSessionNode():ServerNode;

    public function getRequestExecutor():RequestExecutor;

    public function getSessionInfo():SessionInfo;

    /** TODO CONFIRM THE INTEGRATION OF EVENLISTNER
     * void addBeforeStoreListener(EventHandler<BeforeStoreEventArgs> handler);
    void removeBeforeStoreListener(EventHandler<BeforeStoreEventArgs> handler);

    void addAfterSaveChangesListener(EventHandler<AfterSaveChangesEventArgs> handler);
    void removeAfterSaveChangesListener(EventHandler<AfterSaveChangesEventArgs> handler);

    void addBeforeDeleteListener(EventHandler<BeforeDeleteEventArgs> handler);
    void removeBeforeDeleteListener(EventHandler<BeforeDeleteEventArgs> handler);

    void addBeforeQueryListener(EventHandler<BeforeQueryEventArgs> handler);
    void removeBeforeQueryListener(EventHandler<BeforeQueryEventArgs> handler);

    void addBeforeConversionToDocumentListener(EventHandler<BeforeConversionToDocumentEventArgs> handler);
    void removeBeforeConversionToDocumentListener(EventHandler<BeforeConversionToDocumentEventArgs> handler);

    void addAfterConversionToDocumentListener(EventHandler<AfterConversionToDocumentEventArgs> handler);
    void removeAfterConversionToDocumentListener(EventHandler<AfterConversionToDocumentEventArgs> handler);

    void addBeforeConversionToEntityListener(EventHandler<BeforeConversionToEntityEventArgs> handler);
    void removeBeforeConversionToEntityListener(EventHandler<BeforeConversionToEntityEventArgs> handler);

    void addAfterConversionToEntityListener(EventHandler<AfterConversionToEntityEventArgs> handler);
    void removeAfterConversionToEntityListener(EventHandler<AfterConversionToEntityEventArgs> handler);
    */

    /**
     * Gets a value indicating whether any of the entities tracked by the session has changes.
     * @return true if any entity associated with session has changes
     */
    public function hasChanges():bool;

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


}
