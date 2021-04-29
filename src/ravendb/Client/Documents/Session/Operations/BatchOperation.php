<?php

namespace RavenDB\Client\Documents\Session\Operations;

use Ds\Map;
use RavenDB\Client\Documents\Commands\Batches\BatchCommandResult;
use RavenDB\Client\Documents\Commands\Batches\SingleNodeBatchCommand;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

class BatchOperation
{
    private InMemoryDocumentSessionOperations $_session;
    /**
     * @psalm-var List<Object>
     */
    private array $_entities;
    private int $_sessionCommandsCount;
    private int $_allCommandsCount;
    private Map $_modifications;
    public function __construct(InMemoryDocumentSessionOperations $_session)
    {
        $this->_session = $_session;
    }

    /**
     * @throws \Exception
     */
    public function createRequest(): SingleNodeBatchCommand {
        $result = $this->_session->prepareForSaveChanges();
        return new SingleNodeBatchCommand($this->_session->getConvetions(), $result->getSessionCommands(),$result->getOptions());
    }

    /**
     * @throws \Exception
     * TODO : COMPLETE THE RESULT MIGRATIONS
     */
    public function setResult(BatchCommandResult $result){

        if(null === $result->getResults()){
            throw new \Exception("throwOnNullResults");
        }
        return $result->getResults();
    }

    private function handleCompareExchangePut():void {
    }

    private function handleCompareExchangeDelete():void {
    }

    private function handleCompareExchangeInternal():void{
    }
}
