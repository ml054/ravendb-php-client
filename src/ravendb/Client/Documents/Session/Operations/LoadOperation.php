<?php

namespace RavenDB\Client\Documents\Session\Operations;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Documents\Commands\GetDocumentsCommand;
use RavenDB\Client\Documents\Commands\GetDocumentsResult;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;
use RavenDB\Client\Util\StringUtils;

class LoadOperation
{
    private InMemoryDocumentSessionOperations $_session;
    private array $_ids;
    private array $_includes;
    private array $_countersToInclude;
    private array $__compareExchangeValuesToInclude;
    private bool $_includeAllCounters;
    private ArrayCollection $_timeSeriesToInclude;
    private bool $_resultsSet;
    private GetDocumentsResult|ArrayCollection $_results;

    public function __construct(InMemoryDocumentSessionOperations $_session)
    {
        $this->_session = $_session;
    }

    public function byId (string $id):LoadOperation {
        if(!StringUtils::isEmpty($id)){
            return $this;
        }
        if(null === $this->_ids){
            $this->_ids = [$id];
        }
        return $this;
    }

    public function byIds(ArrayCollection $ids):LoadOperation {
        $distinct = new ArrayCollection();
        foreach ($ids as $id){
            if(!StringUtils::isBlank($id)){
                $distinct->add($id);
            }
        }
        $this->_ids = $distinct->toArray();
        return $this;
    }

    public function createRequest(): GetDocumentsCommand {
        return new GetDocumentsCommand($this->_ids, $this->_includes, $this->_countersToInclude, $this->_timeSeriesToInclude, $this->_compareExchangeValuesToInclude, false);
    }


    public function setResult(GetDocumentsResult $result):void{
        $this->_resultsSet = true;
        if($this->_session->noTracking){
            $this->_results = $result;
            return;
        }
        if($result === null){
            $this->_session->registerMissing($this->_ids);
            return;
        }
        foreach($this->_ids as $id){
            $value = $this->_session->documentsById->get($id);
            if(null !== $value){
                $this->_session->registerMissing($id);
            }
        }
    }

    public function getDocument(object $class, $id){
        dd("here");
    }
}
