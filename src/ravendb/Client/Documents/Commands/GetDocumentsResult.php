<?php

namespace RavenDB\Client\Documents\Commands;

use Doctrine\Common\Collections\ArrayCollection;

class GetDocumentsResult
{
    private ArrayCollection $includes;
    private ArrayCollection $results;
    private ArrayCollection $counterIncludes;
    private ArrayCollection $timeSeriesIncludes;
    private ArrayCollection $compareExchangeValueIncludes;
    private int $nextPageStart;

    public function getIncludes(): ArrayCollection
    {
        return $this->includes;
    }

    public function getResults(): ArrayCollection
    {
        return $this->results;
    }

    public function getCounterIncludes(): ArrayCollection
    {
        return $this->counterIncludes;
    }

    public function getTimeSeriesIncludes(): ArrayCollection
    {
        return $this->timeSeriesIncludes;
    }

    public function getCompareExchangeValueIncludes(): ArrayCollection
    {
        return $this->compareExchangeValueIncludes;
    }

    public function getNextPageStart(): int
    {
        return $this->nextPageStart;
    }

    public function setResults(ArrayCollection $results): void
    {
        $this->results = $results;
    }


}
