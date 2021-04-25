<?php

namespace RavenDB\Client\Documents\Session\Operations;

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
        return new SingleNodeBatchCommand($this->_session->getConvetions(), (array)$result->getSessionCommands(),$result->getOptions());
    }

    private function handleCompareExchangePut():void {
    }

    private function handleCompareExchangeDelete():void {
    }

    private function handleCompareExchangeInternal():void{
    }
}
