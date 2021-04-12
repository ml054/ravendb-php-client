<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Util\Duration;

class Parameters {
        private string $databaseNames;
        private bool $hardDelete;
        private array $fromNodes;
        private Duration $timeToWaitForConfirmation;

    /**
     * @return string
     */
    public function getDatabaseNames(): string
    {
        return $this->databaseNames;
    }

    /**
     * @param string $databaseNames
     */
    public function setDatabaseNames(string|array $databaseNames): void
    {
        $this->databaseNames = $databaseNames;
    }

    /**
     * @return bool
     */
    public function isHardDelete(): bool
    {
        return $this->hardDelete;
    }

    /**
     * @param bool $hardDelete
     */
    public function setHardDelete(bool $hardDelete): void
    {
        $this->hardDelete = $hardDelete;
    }

    /**
     * @return array
     */
    public function getFromNodes(): array
    {
        return $this->fromNodes;
    }

    /**
     * @param array $fromNodes
     */
    public function setFromNodes(array|string $fromNodes): void
    {
        $this->fromNodes = $fromNodes;
    }

    /**
     * @return \RavenDB\Client\Util\Duration
     */
    public function getTimeToWaitForConfirmation(): Duration
    {
        return $this->timeToWaitForConfirmation;
    }

    /**
     * @param \RavenDB\Client\Util\Duration $timeToWaitForConfirmation
     */
    public function setTimeToWaitForConfirmation(Duration $timeToWaitForConfirmation): void
    {
        $this->timeToWaitForConfirmation = $timeToWaitForConfirmation;
    }
}

