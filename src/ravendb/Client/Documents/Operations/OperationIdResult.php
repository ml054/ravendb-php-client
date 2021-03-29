<?php

namespace RavenDB\Client\Documents\Operations;
/**
 * The result of a OperationIdResult operation
 */
class OperationIdResult
{
    private string $operationID;
    private string $operationNodeTag;

    /**
     * @return string
     */
    public function getOperationID(): string
    {
        return $this->operationID;
    }

    /**
     * @param string $operationID
     */
    public function setOperationID(string $operationID): void
    {
        $this->operationID = $operationID;
    }

    /**
     * @return string
     */
    public function getOperationNodeTag(): string
    {
        return $this->operationNodeTag;
    }

    /**
     * @param string $operationNodeTag
     */
    public function setOperationNodeTag(string $operationNodeTag): void
    {
        $this->operationNodeTag = $operationNodeTag;
    }

}
