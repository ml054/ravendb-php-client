<?php

namespace RavenDB\Client\Documents;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Indexes\IAbstractIndexCreationTask;
use RavenDB\Client\Documents\Operations\MaintenanceOperationExecutor;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Session\IDocumentSession;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Documents\Smuggler\DatabaseSmuggler;
use RavenDB\Client\Documents\TimeSeries\TimeSeriesOperations;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\IDisposalNotification;

interface IDocumentStore extends IDisposalNotification
{
    /**
     * Subscribe to change notifications from the server
     * @return string
     */

    public function getIdentifier(): string;

    public function setIdentifier(string $identifier): void;

    public function initialize(): IDocumentStore;

    public function openSession(SessionOptions $sessionOptions): IDocumentSession;

    function executeIndex(IAbstractIndexCreationTask $task, string $database): void;

    function executeIndexes(IAbstractIndexCreationTask $tasks): void;

    public function getConventions(): DocumentConventions;

    public function getUrls(): array|string;

    public function bulkInsert(string $database): BulkInsertOperation;

    public function getDatabase(): ?string;

    public function getRequestExecutor(?string $databaseName=null): RequestExecutor;

    public function timeSeries(): TimeSeriesOperations;

    public function maintenance(): MaintenanceOperationExecutor;

    public function operations(): OperationExecutor;

    public function smuggler(): DatabaseSmuggler;

    public function setRequestTimeout(int $timeout, ?string $database): Closable;
}
