<?php

namespace RavenDB\Tests\Client\CrudEntities;

use RavenDB\Client\Infrastructure\Entities\User;

class Poc
{
    private string $name;
    private ?User $obj;
    private ?string $id;

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getObj(): ?User
    {
        return $this->obj;
    }

    public function setObj(?User $obj): void
    {
        $this->obj = $obj;
    }

}
