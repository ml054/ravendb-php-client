<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Util\Duration;

class Parameters {

        private ?string $databaseNames=null;
        private bool $hardDelete=true;
        private ?string $fromNodes=null;
        private ?int $timeToWaitForConfirmation=null;

    /**
         * @return string|null
         */
        public function getDatabaseNames(): ?string
        {
            return $this->databaseNames;
        }

    /**
     * @param string $databaseNames
     */
    public function setDatabaseNames(string $databaseNames): void
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
     * @return string
     */
    public function getFromNodes(): ?string
    {
        return $this->fromNodes;
    }

    /**
     * @param string|null $fromNodes
     */
    public function setFromNodes(string|null $fromNodes): void
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

