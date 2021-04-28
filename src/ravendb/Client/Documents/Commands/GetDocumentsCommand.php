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
    private ArrayCollection $_includes;
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

    public function __construct(string $id,ArrayCollection|null $includes, ?bool $metadataOnly)
    {
        parent::__construct(GetDocumentsResult::class);
        $this->id = $id;
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
        $url = $node->getUrl()."/databases/".$node->getDatabase()."/docs?id=".urlencode($this->id);
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
      //  dd($curlopt,__METHOD__);
        return $httpClient->createCurlRequest($url,$curlopt);
    }

    public function setResponse(array|string $response, bool $fromCache)
    {
        if(null === $response){
            return;
        }
        dd($response);
        $this->result = $this->mapper()::readValue($response,GetDocumentsResult::class);
    }
}
