<?php

namespace RavenDB\Client\Documents\Commands;

use Doctrine\Common\Collections\ArrayCollection;

class GetDocumentsResult
{
    private mixed $includes;
    private $results;
    private mixed $counterIncludes;
    private mixed $timeSeriesIncludes;
    private mixed $compareExchangeValueIncludes;
    private int $nextPageStart;

    public function getIncludes()
    {
        return $this->includes;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function getCounterIncludes()
    {
        return $this->counterIncludes;
    }

    public function getTimeSeriesIncludes()
    {
        return $this->timeSeriesIncludes;
    }

    public function getCompareExchangeValueIncludes()
    {
        return $this->compareExchangeValueIncludes;
    }

    public function getNextPageStart(): int
    {
        return $this->nextPageStart;
    }

    public function setResults($results): void
    {
        $this->results = $results;
    }

    public function setIncludes(mixed $includes): void
    {
        $this->includes = $includes;
    }

    public function setCounterIncludes(mixed $counterIncludes): void
    {
        $this->counterIncludes = $counterIncludes;
    }

    public function setTimeSeriesIncludes(mixed $timeSeriesIncludes): void
    {
        $this->timeSeriesIncludes = $timeSeriesIncludes;
    }

    public function setCompareExchangeValueIncludes(mixed $compareExchangeValueIncludes): void
    {
        $this->compareExchangeValueIncludes = $compareExchangeValueIncludes;
    }

    public function setNextPageStart(int $nextPageStart): void
    {
        $this->nextPageStart = $nextPageStart;
    }
}
