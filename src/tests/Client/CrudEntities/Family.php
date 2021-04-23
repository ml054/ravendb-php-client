<?php

namespace RavenDB\Tests\Client\CrudEntities;

class Family
{
    private array $names;
    private string $id;

    public function getNames(): array
    {
        return $this->names;
    }

    public function setNames(array $names): self
    {
        $this->names = $names;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Family
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }
}
