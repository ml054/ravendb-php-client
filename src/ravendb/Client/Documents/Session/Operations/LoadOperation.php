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
class LoadOperation
{
    use ObjectMapper;
    private InMemoryDocumentSessionOperations $_session;
    private ?ArrayCollection $_ids=null;
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

        $this->_session->incrementRequestCount();
        return new GetDocumentsCommand($this->_ids,null,null,null,null,null,null,null,null);
    }

    public function byId (string $id): LoadOperation
    {
        if( StringUtils::isEmpty($id)){ return $this; }
        if(null === $this->_ids){
            $this->_ids = new ArrayCollection([$id]);
        }
        return $this;
    }

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

        if($this->_session->isDeleted($id)) return Defaults::defaultValue($clazz);

        $doc = $this->_session->documentsById->getValue($id);
        if(null !== $doc) return $this->_session->trackEntity($clazz,$doc,$id); // adding the id as available to bypass trackEntity exception

        $doc = $this->_session->includedDocumentsById->get($id);
        if(null !== $doc) return $this->_session->trackEntity($clazz,$doc,$id); // adding the id as available to bypass trackEntity exception

        return Defaults::defaultValue($clazz);
    }

    public function getDocuments($clazz): Map
    {
        $finalResults = new Map();
        if($this->_session->noTracking){
            if(!$this->_resultsSet && count($this->_ids) > 0) throw new \Exception("Cannot execute getDocument before operation execution.");
            foreach($this->_ids as $id){
                if( null === $id ) continue;
                $finalResults->put($id,null);
            }
            if(null === $this->_results || null === $this->_results->getResults() || 0 === $this->_results->getResults()->count()) return $finalResults;

            foreach($this->_results->getResults() as $document){
                if(null === $document || empty($document)) continue;
                $newDocumentInfo = DocumentInfo::getNewDocumentInfo($document);
                $finalResults->put($newDocumentInfo->getId(),$this->_session->trackEntity($clazz,$newDocumentInfo));
            }
            return $finalResults;
        }
        foreach($this->_ids as $id){
            if(null === $id) continue;
            $finalResults->put($id,$this->getDocument($clazz,$id));
        }
        return $finalResults;
    }

    /**
     * @throws \Exception
     */
    public function setResult(GetDocumentsResult $result):void{
        $this->_resultsSet = true;
        if($this->_session->noTracking){
            $this->_results = $result;
        }
        if(null === $result) {
            $this->_session->registerMissing($this->_ids);
            return;
        }
        if(!empty($result->getIncludes())) $this->_session->registerIncludes($result->getIncludes());

        foreach($result->getResults() as $document){
            if(null === $document || is_null($document)) continue;
            $newDocumentInfo = DocumentInfo::getNewDocumentInfo($document);
            $this->_session->documentsById->add($newDocumentInfo);
        }

        if(is_array($this->_ids)){
            foreach($this->_ids as $id){
                $value = $this->_session->documentsById->getValue($id);
                if(null === $value ) $this->_session->registerMissing($id);
            }
        }
    }
}
