<?php

namespace RavenDB\Tests\Client\CrudEntities;

class Family
{
    private array $names;

    public function getNames(): array
    {
        return $this->names;
    }

    public function setNames(array $names): self
    {
        $this->names = $names;
        return $this;
    }
}
