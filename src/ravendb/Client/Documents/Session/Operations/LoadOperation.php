<?php /** @noinspection ALL */

namespace RavenDB\Client\Documents\Session\Operations;

use Doctrine\Common\Collections\ArrayCollection;
use Ds\Map;
use http\Exception;
use RavenDB\Client\DataBind\Node\ObjectNode;
use RavenDB\Client\Defaults;
use RavenDB\Client\Documents\Commands\GetDocumentsCommand;
use RavenDB\Client\Documents\Commands\GetDocumentsResult;
use RavenDB\Client\Documents\Operations\TimeSeries\TimeSeriesRange;
use RavenDB\Client\Documents\Session\DocumentInfo;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Util\ObjectMapper;
use RavenDB\Client\Util\StringUtils;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class LoadOperation
{
    use ObjectMapper;
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
    private $mapper;

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
    // FINAL
    public function byIds(array $ids):self {
        $distinct = new ArrayCollection();
        foreach($ids as $id){
            $distinct->add($id);
        }
        $this->_ids = $distinct->toArray();
        return $this;
    }
    public function  withIncludes(array $includes):self {
        $this->_includes = $includes;
        return $this;
    }

    public function getDocument($clazz,?string $id=null)
    {
        if(null === $id){
            if($this->_session->noTracking){
                if(!$this->_resultsSet && count($this->_ids) > 0) throw new \Exception("Cannot execute getDocument before operation execution.");
                if(null === $this->_results || null === $this->_results->getResults() || 0 === $this->_results->getResults()->count()) return null;
                $document = $this->_results->getResults()->get(0);
                if(null === $document) return null;
                $documentInfo = DocumentInfo::getNewDocumentInfo($document);
                return $this->_session->trackEntity($clazz,$documentInfo);
            }
        }else{
            if($this->_session->isDeleted($id))return Defaults::defaultValue($clazz);
            $doc = $this->_session->documentsById->getValue($id);
            if(null !== $doc) return $this->_session->trackEntity($clazz,$doc);
            return Defaults::defaultValue($clazz);
        }
    }

    public function getDocuments($clazz): Map {
        $finalResults = new Map();
        if($this->_session->noTracking){
            if(!$this->_resultsSet && count($this->_ids) > 0) throw new \Exception("Cannot execute getDocument before operation execution.");
            foreach($this->_ids as $id){
                if( null === $id ) continue;
                $finalResults->put($id,null);
            }
            if(null === $this->_results || null === $this->_results->getResults() || 0 === $this->_results->getResults()->count()) return $finalResults;

            foreach($this->_results->getResults() as $document){
                if(null === $document)
            }
        }
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
        $this->_session->registerIncludes($result->getIncludes());
        foreach($result->getResults() as $document){
            if(empty($document)) continue;
            $newDocument = DocumentInfo::getNewDocumentInfo($document);
            $this->_session->documentsById->add($newDocument);
        }
        dd("hereere");
    }

}
