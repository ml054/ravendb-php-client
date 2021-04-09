<?php

namespace RavenDB\Client\Documents\Commands;

class ReplicationBatchOptions
{
    private bool $waitForReplicas;
    private int $numberOfReplicasToWaitFor;
    private float $waitForReplicasTimeout; // private Duration waitForReplicasTimeout;
    private bool $majority;
    private bool $throwOnTimeoutInWaitForReplicas = true;

    public function isWaitForReplicas(): bool
    {
        return $this->waitForReplicas;
    }

    /**
     * @param bool $waitForReplicas
     */
    public function setWaitForReplicas(bool $waitForReplicas): void
    {
        $this->waitForReplicas = $waitForReplicas;
    }


    /**
     * @return int
     */
    public function getNumberOfReplicasToWaitFor(): int
    {
        return $this->numberOfReplicasToWaitFor;
    }

    /**
     * @param int $numberOfReplicasToWaitFor
     */
    public function setNumberOfReplicasToWaitFor(int $numberOfReplicasToWaitFor): void
    {
        $this->numberOfReplicasToWaitFor = $numberOfReplicasToWaitFor;
    }

    /**
     * @return float
     */
    public function getWaitForReplicasTimeout(): float
    {
        return $this->waitForReplicasTimeout;
    }

    /**
     * @param float $waitForReplicasTimeout
     */
    public function setWaitForReplicasTimeout(float $waitForReplicasTimeout): void
    {
        $this->waitForReplicasTimeout = $waitForReplicasTimeout;
    }

    public function isMajority():bool{
        return $this->majority;
    }

    /**
     * @param bool $majority
     */
    public function setMajority(bool $majority): void
    {
        $this->majority = $majority;
    }

    /**
     * @param bool $throwOnTimeoutInWaitForReplicas
     */
    public function setThrowOnTimeoutInWaitForReplicas(bool $throwOnTimeoutInWaitForReplicas): void
    {
        $this->throwOnTimeoutInWaitForReplicas = $throwOnTimeoutInWaitForReplicas;
    }

    public function isThrowOnTimeoutInWaitForReplicas(): bool
    {
        return $this->throwOnTimeoutInWaitForReplicas;
    }
}
