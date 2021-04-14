<?php

namespace RavenDB\Client\Documents\Commands\Batches;
// EMMULATE JAVA RESULT OUTPUT TO INJECT WITH THE MAPPER
class BatchCommandResult
{
    private array $result;
    private float $transactionIndex;

    public function getResult(): array
    {
        return $this->result;
    }

    public function setResult(array $result): void
    {
        $this->result = $result;
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
