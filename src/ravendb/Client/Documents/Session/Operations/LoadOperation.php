<?php

namespace RavenDB\Client\Documents\Session\Operations;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\DataBind\Node\ObjectNode;
use RavenDB\Client\Documents\Commands\GetDocumentsCommand;
use RavenDB\Client\Documents\Commands\GetDocumentsResult;
use RavenDB\Client\Documents\Operations\TimeSeries\TimeSeriesRange;
use RavenDB\Client\Documents\Session\DocumentInfo;
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

    /**
     * @throws \Exception
     */
    public function setResult(GetDocumentsResult $result):void{
        $this->_resultsSet = true;
        if($this->_session->noTracking){
            $this->_results = $result;
            return;
        }
        if($result === null){
            $this->_session->registerMissing([$this->id]);
            return;
        }

        foreach($result->getResults() as $document){
            if(empty($document)) continue;
            // DOCUMENT IS SENT
            $newDocument = DocumentInfo::getNewDocumentInfo($document);
            $this->_session->documentsById->add($newDocument);
        }

        /*foreach($this->_ids as $id){
            $value = $this->_session->documentsById->getValue($id);
            if(null !== $value){
                $this->_session->registerMissing($id);
            }
        }*/
    }

    public function getDocument(object|string $class, $id){
        if($this->_session->isDeleted($id)){
            return $class; // TODO CHECK WITH TECH THE APPROACH FOR DEFAULT CLASS TYPE VALUE. IF NEEDED IN PHP
        }
        $doc = $this->_session->documentsById->getValue($id);
       /* TODO GETS TRIGGERED AS THE DOC IS IN THE SESSION. TRACKENTITY TO PUT IN PLACE
       if($doc !== null){
            return $this->_session->trackEntity($class,$doc);
        }
       */
        return $class;
    }
}
