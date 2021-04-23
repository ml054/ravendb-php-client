<?php

namespace RavenDB\Tests\Client\CrudEntities;

class Member
{
    private $name;
    private int $age;
    private string|null $id;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }
    public function getAge(): int
    {
        return $this->age;
    }
    public function setAge(int $age): self
    {
        $this->age = $age;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

}
