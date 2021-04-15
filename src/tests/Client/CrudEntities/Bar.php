<?php

namespace RavenDB\Tests\Client\CrudEntities;

class Bar
{
    private string $fooId;
    private array $fooIDs;

    public function getFooId(): string
    {
        return $this->fooId;
    }

    public function setFooId(string $fooId): void
    {
        $this->fooId = $fooId;
    }

    public function getFooIDs(): array
    {
        return $this->fooIDs;
    }

    public function setFooIDs(array $fooIDs): void
    {
        $this->fooIDs = $fooIDs;
    }
}
