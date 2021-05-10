<?php

namespace RavenDB\Client\Documents\Commands;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Constants;
use RavenDB\Client\Documents\Operations\TimeSeries\TimeSeriesRange;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Methods\HttpRequestBase;

class GetDocumentsCommand extends RavenCommand
{
    /**
     * @psalm-var List<TimeSeriesRange>
     */
    private  TimeSeriesRange $_timeSeriesIncludes;
    private string $id;
    private ArrayCollection $_ids;
    private ?ArrayCollection $_includes=null;
    private ArrayCollection $_counters;
    private bool $_includeAllCounters;
    private ArrayCollection $_compareExchangeValueIncludes;
    private bool $_metadataOnly;
    private string $_startWith;
    private string $_matches;
    private int $_start;
    private int $_pageSize;
    private string $_exclude;
    private string $_startAfter;

    public function __construct(ArrayCollection $ids,ArrayCollection $includes, bool $metadataOnly, string $startWith, string $startAfter,string $matches,string $exclude, int $start, int $pageSize )
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
        $queryArgs = new ArrayCollection();



       /* $url = $node->getUrl()."/databases/".$node->getDatabase()."/docs?id=".urlencode($this->id);
        $httpClient = new HttpRequestBase();
        $curlopt = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER =>Constants::CURLOPT_RETURNTRANSFER,
            CURLOPT_SSL_VERIFYHOST=>Constants::CURLOPT_SSL_VERIFYHOST,
            CURLOPT_SSL_VERIFYPEER=>Constants::CURLOPT_SSL_VERIFYPEER,
            CURLOPT_CUSTOMREQUEST=>Constants::CURLOPT_CUSTOMREQUEST_GET,
            CURLOPT_HTTPHEADER=>[ // The CURLOPT_HTTPHEADER option must have an array value
                Constants::HEADERS_CONTENT_TYPE_APPLICATION_JSON
            ]
        ];

        return $httpClient->createCurlRequest($url,$curlopt);*/
    }

    public function setResponse(array|string $response, bool $fromCache)
    {
        $this->result = $this->mapper()::readValue($response,GetDocumentsResult::class);
    }
}
