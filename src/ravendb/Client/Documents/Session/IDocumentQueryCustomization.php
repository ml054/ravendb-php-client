<?php

namespace RavenDB\Client\Documents\Session;

interface IDocumentQueryCustomization
{
    /**
     * Get the raw query operation that will be sent to the server
     * @return QueryOperation
     */
    function getQueryOperation():QueryOperation;

    /**
     * Allow you to modify the index query before it is executed
     * @param $action
     * @return IDocumentQueryCustomization
     */
    function addBeforeQueryExecutedListener(string $action):IDocumentQueryCustomization;

    /**
     * Allow you to modify the index query before it is executed
     * @param $action
     * @return IDocumentQueryCustomization
     */
    function removeBeforeQueryExecutedListener(string $action):IDocumentQueryCustomization;

    /**
     * Callback to get the results of the query
     * @param $action
     * @return IDocumentQueryCustomization
     */
    function addAfterQueryExecutedListener(string $action):IDocumentQueryCustomization;

    /**
     * Callback to get the results of the query
     * @param $action
     * @return IDocumentQueryCustomization
     */
    function removeAfterQueryExecutedListener(string $action):IDocumentQueryCustomization;

    /**
     * Callback to get the raw objects streamed by the query
     * @param $action
     * @return IDocumentQueryCustomization
     */
    function addAfterStreamExecutedCallback(string $action):IDocumentQueryCustomization;

    /**
     * Callback to get the raw objects streamed by the query
     * @param $action
     * @return IDocumentQueryCustomization
     */
    function removeAfterStreamExecutedCallback(string $action):IDocumentQueryCustomization;

    /**
     * Disables caching for query results.
     * @return IDocumentQueryCustomization
     */
    function noCaching():IDocumentQueryCustomization;

    /**
     * Disables tracking for queried entities by Raven's Unit of Work.
     * Usage of this option will prevent holding query results in memory.
     * @return IDocumentQueryCustomization
     */
    function noTracking():IDocumentQueryCustomization;

    /**
     * Disables tracking for queried entities by Raven's Unit of Work.
     * Usage of this option will prevent holding query results in memory.
     * @return IDocumentQueryCustomization
     */
    function randomOrdering():IDocumentQueryCustomization;
    function timings($timings):IDocumentQueryCustomization;

    /**
     * Instruct the query to wait for non stale results.
     * This shouldn't be used outside of unit tests unless you are well aware of the implications
     * @return IDocumentQueryCustomization
     */
    function waitForNonStaleResults(?int $waitTimeout=null):IDocumentQueryCustomization;



}
