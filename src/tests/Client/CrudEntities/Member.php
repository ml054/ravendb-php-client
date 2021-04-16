<?php

namespace RavenDB\Tests\Client\CrudEntities;

class Member
{
    private $name;
    private int $age;

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
}
