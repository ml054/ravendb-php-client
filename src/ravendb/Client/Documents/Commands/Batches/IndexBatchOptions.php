<?php

namespace RavenDB\Client\Documents\Commands\Batches;

use Doctrine\Common\Collections\ArrayCollection;

class IndexBatchOptions
{
    private bool $waitForIndexes;
    private int $waitForIndexesTimeout;
    private bool $throwOnTimeoutInWaitForIndexes;
    private ArrayCollection $waitForSpecificIndexes;

    public function isWaitForIndexes(): bool
    {
        return $this->waitForIndexes;
    }

    public function setWaitForIndexes(bool $waitForIndexes): void
    {
        $this->waitForIndexes = $waitForIndexes;
    }

    public function getWaitForIndexesTimeout(): int
    {
        return $this->waitForIndexesTimeout;
    }

    public function setWaitForIndexesTimeout(int $waitForIndexesTimeout): void
    {
        $this->waitForIndexesTimeout = $waitForIndexesTimeout;
    }

    public function isThrowOnTimeoutInWaitForIndexes(): bool
    {
        return $this->throwOnTimeoutInWaitForIndexes;
    }

    public function setThrowOnTimeoutInWaitForIndexes(bool $throwOnTimeoutInWaitForIndexes): void
    {
        $this->throwOnTimeoutInWaitForIndexes = $throwOnTimeoutInWaitForIndexes;
    }

    public function getWaitForSpecificIndexes(): ArrayCollection
    {
        return $this->waitForSpecificIndexes;
    }

    public function setWaitForSpecificIndexes(ArrayCollection $waitForSpecificIndexes): void
    {
        $this->waitForSpecificIndexes = $waitForSpecificIndexes;
    }


}
