<?php

namespace RavenDB\Client\Documents\Commands\Batches;

class BatchCommandResult
{
    private BatchCommandArrayNodeResult $results;
    private float $transactionIndex;

    public function getResults(): BatchCommandArrayNodeResult
    {
        return $this->results;
    }

    public function setResults(BatchCommandArrayNodeResult $results): void
    {
        $this->results = $results;
    }

    public function getTransactionIndex(): float
    {
        return $this->transactionIndex;
    }

    public function setTransactionIndex(float $transactionIndex): void
    {
        $this->transactionIndex = $transactionIndex;
    }
}
