<?php

namespace RavenDB\Client\Documents;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Operations\MaintenanceOperationExecutor;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Documents\Smuggler\DatabaseSmuggler;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\IDisposalNotification;
// TODO: IMPORT FROM JVM CLIENT MISSING CLASSES
interface IDocumentStore extends IDisposalNotification
{
    /**
     * Subscribe to change notifications from the server
     * @return string
     */

    public function getIdentifier(): string;

    public function setIdentifier(string $identifier): void;

    public function initialize(): IDocumentStore;

    public function openSession(SessionOptions $sessionOptions): IDocumentStore;

    function executeIndex(IAbstractIndexCreationTask $task, string $database): void;

    function executeIndexes(IAbstractIndexCreationTask $tasks): void; // TODO Interface IAbstractIndexCreationTask MIGRATION

    public function getConventions(): DocumentConventions;

    public function getUrls(): array|string;

    public function bulkInsert(string $database): BulkInsertOperation; // TODO BulkInsertOperation MIGRATION

    public function getDatabase(): string;

    public function getRequestExecutor(?string $databaseName): RequestExecutor;

    public function timeSeries(): TimeSeriesOperations; // TODO TimeSeriesOperations MIGRATION

    public function maintenance(): MaintenanceOperationExecutor;

    public function operations(): OperationExecutor;

    public function smuggler(): DatabaseSmuggler;

    public function setRequestTimeout(int $timeout, ?string $database): Closable;
}
