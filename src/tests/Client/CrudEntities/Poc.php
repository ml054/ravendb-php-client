<?php

namespace RavenDB\Tests\Client\CrudEntities;

use RavenDB\Client\Infrastructure\Entities\User;

class Poc
{
    private string $name;
    private ?User $obj;
    private ?string $id;
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return User|null
     */
    public function getObj(): ?User
    {
        return $this->obj;
    }

    /**
     * @param ?User $obj
     */
    public function setObj(?User $obj): void
    {
        $this->obj = $obj;
    }

}
