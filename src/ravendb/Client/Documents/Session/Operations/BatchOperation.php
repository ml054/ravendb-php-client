<?php

namespace RavenDB\Client\Documents\Session\Operations;

use RavenDB\Client\Documents\Commands\Batches\BatchCommandResult;
use RavenDB\Client\Documents\Commands\Batches\SingleNodeBatchCommand;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

class BatchOperation
{
    private InMemoryDocumentSessionOperations $_session;
    private array $_entities;
    private int $_sessionCommandsCount;
    private int $_allCommandsCount;

    public function __construct(InMemoryDocumentSessionOperations $_session)
    {
        $this->_session = $_session;
    }

    public function createRequest(): SingleNodeBatchCommand {
        $result = $this->_session->prepareForSaveChanges();
        dd($result->getEntities());
        return new SingleNodeBatchCommand($this->_session->getConvetions(), $result->getSessionCommands(),$result->getOptions());
    }

    public function setResult(BatchCommandResult $result){
        dd(__METHOD__,"command result");
    }

    private function handleCompareExchangePut():void {
    }

    private function handleCompareExchangeDelete():void {
    }

    private function handleCompareExchangeInternal():void{
    }
}
