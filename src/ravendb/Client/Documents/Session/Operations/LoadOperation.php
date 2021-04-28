<?php

namespace RavenDB\Client\Documents\Session\Operations;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Documents\Commands\GetDocumentsCommand;
use RavenDB\Client\Documents\Commands\GetDocumentsResult;
use RavenDB\Client\Documents\Operations\TimeSeries\TimeSeriesRange;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;
use RavenDB\Client\Util\StringUtils;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class LoadOperation
{
    private InMemoryDocumentSessionOperations $_session;
    private string $_ids;
    private string $id;
    private array $_includes;
    private ArrayCollection $_countersToInclude;
    private ArrayCollection $_compareExchangeValuesToInclude;
    private bool $_includeAllCounters;

    /*** @psalm-return List<TimeSeriesRange> */
    private ArrayCollection $_timeSeriesToInclude;

    private bool $_resultsSet;
    private ?int $_start;
    private ?int $_pageSize;
    private GetDocumentsResult $_results;

    public function __construct(InMemoryDocumentSessionOperations $_session)
    {
        $this->_session = $_session;
    }

    /**
     * @throws \Exception
     */
    public function createRequest(): ?GetDocumentsCommand {
        /*if($this->_session->checkIfIdAlreadyIncluded($this->_ids,$this->_includes !== null ? new ArrayCollection($this->_includes): null)){
            return null;
        }*/
        $this->_session->incrementRequestCount();
        return new GetDocumentsCommand($this->_ids,null,null);
    }

    public function byId (string $id): LoadOperation {
        if(StringUtils::isEmpty($id)){
            return $this;
        }
        $this->_ids = $id;
        return $this;
    }

    public function byIds(ArrayCollection $ids):LoadOperation {
        $distinct = new ArrayCollection();
        foreach ($ids as $id){
            if(!StringUtils::isEmpty($id)){
                $distinct->add($id);
            }
        }
        $this->_ids = $distinct->getValues();
        return $this;
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
            $value = $this->_session->documentsById->getValue($id);
            if(null !== $value){
                $this->_session->registerMissing($id);
            }
        }
    }

    public function getDocument(object|string $class, $id){
    }
}
