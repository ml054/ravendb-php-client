<?php

namespace RavenDB\Client\Documents\Commands;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Constants;
use RavenDB\Client\Documents\Operations\TimeSeries\TimeSeriesRange;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Methods\HttpRequestBase;
use RavenDB\Client\Util\UrlUtils;

class GetDocumentsCommand extends RavenCommand
{
    /**
     * @psalm-var List<TimeSeriesRange>
     */
    private  TimeSeriesRange $_timeSeriesIncludes;
   // private ?string $_id=null;
    private ArrayCollection|string $_ids;
    private ?ArrayCollection $_includes=null;
    private ArrayCollection $_counters;
    private bool $_includeAllCounters;
    private ArrayCollection $_compareExchangeValueIncludes;
    private ?bool $_metadataOnly=null;
    private ?string $_startWith=null;
    private ?string $_matches=null;
    private ?int $_start=null;
    private ?int $_pageSize=null;
    private ?string $_exclude=null;
    private ?string $_startAfter=null;

    public function __construct( ArrayCollection|string $ids,?ArrayCollection $includes, ?bool $metadataOnly, ?string $startWith, ?string $startAfter, ?string $matches, ?string $exclude, ?int $start, ?int $pageSize )
    {
        parent::__construct(GetDocumentsResult::class);
        $this->_ids = $ids;
        $this->_includes = $includes;
        $this->_metadataOnly = $metadataOnly;
        $this->_startWith = $startWith;
        $this->_startAfter = $startAfter;
        $this->_matches = $matches;
        $this->_exclude = $exclude;
        $this->_start = $start;
        $this->_pageSize = $pageSize;
    }

    public function isReadRequest(): bool
    {
        return true;
    }

    /**
     * @throws \Exception
     */
    public function createRequest(ServerNode $node): \CurlHandle
    {
        $url = $node->getUrl()."/databases/".$node->getDatabase()."/docs?";
        $pathBuilder = new ArrayCollection();

        if(null !== $this->_start){ $pathBuilder->set("start",$this->_start); }
        if(null !== $this->_pageSize){ $pathBuilder->set("pageSize",$this->_pageSize); }
        if(null !== $this->_metadataOnly){ $pathBuilder->set("metadataOnly",$this->_metadataOnly); }

        if(null !== $this->_startWith){
            $pathBuilder->set("startsWith",$this->_startWith);
            if(null !== $this->_matches){ $pathBuilder->set("matches",urlencode($this->_matches));}
            if(null !== $this->_exclude){ $pathBuilder->set("exclude",urlencode($this->_exclude));}
            if(null !== $this->_startAfter){ $pathBuilder->set("startAfter",$this->_startAfter); }
        }
        if(null !== $this->_ids || is_string($this->_ids)){
            $pathBuilder->set("id",$this->_ids->get(0));
        } elseif(null !== $this->_ids && is_array($this->_ids)){
            throw new \InvalidArgumentException("prepareRequestWithMultipleIds not yet implemented");
        }
        $path = $url.UrlUtils::pathBuilder($pathBuilder->toArray());
        $httpClient = new HttpRequestBase();
        $curlopt = [
            CURLOPT_URL => $path,
            CURLOPT_RETURNTRANSFER =>Constants::CURLOPT_RETURNTRANSFER,
            CURLOPT_SSL_VERIFYHOST=>Constants::CURLOPT_SSL_VERIFYHOST,
            CURLOPT_SSL_VERIFYPEER=>Constants::CURLOPT_SSL_VERIFYPEER,
            CURLOPT_CUSTOMREQUEST=>Constants::CURLOPT_CUSTOMREQUEST_GET,
            CURLOPT_HTTPHEADER=>[ // The CURLOPT_HTTPHEADER option must have an array value
                Constants::HEADERS_CONTENT_TYPE_APPLICATION_JSON
            ]
        ];
        return $httpClient->createCurlRequest($url,$curlopt);
    }

    public function setResponse(array|string $response, bool $fromCache)
    {
        $this->result = $this->mapper()::readValue($response,GetDocumentsResult::class);
        dd($this->result,$response);
    }
}
