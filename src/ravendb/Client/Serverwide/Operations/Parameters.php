<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Util\Duration;

class Parameters {

        private array $databaseNames;
        private bool $hardDelete=true;
        private array $fromNodes;
        private ?int $timeToWaitForConfirmation=null;

    /**
         * @return array|null
         */
        public function getDatabaseNames(): array
        {
            return $this->databaseNames;
        }

    /**
     * @param array $databaseNames
     */
    public function setDatabaseNames(array $databaseNames): void
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
    public function getFromNodes(): ?array
    {
        return $this->fromNodes;
    }

    /**
     * @param array $fromNodes
     */
    public function setFromNodes(array $fromNodes): void
    {
        $this->fromNodes = $fromNodes;
    }

    /**
     * @return int
     */
    public function getTimeToWaitForConfirmation(): ?int
    {
        return $this->timeToWaitForConfirmation;
    }

    /**
     * @param int $timeToWaitForConfirmation
     */
    public function setTimeToWaitForConfirmation(int|null $timeToWaitForConfirmation): void
    {
        $this->timeToWaitForConfirmation = $timeToWaitForConfirmation;
    }
}

