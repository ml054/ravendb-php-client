<?php

namespace RavenDB\Client\Documents\Commands\Batches;
/**
 * The result of a PUT operation
 */
class PutResult
{
    private string $id;
    private string $changeVector;

    public function getId(): string
    {
        return $this->id;
    }
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getChangeVector(): string
    {
        return $this->changeVector;
    }
    public function setChangeVector(string $changeVector): void
    {
        $this->changeVector = $changeVector;
    }
}
