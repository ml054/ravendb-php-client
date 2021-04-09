<?php

namespace RavenDB\Tests\Client;

use RavenDB\Client\Infrastructure\Entities\User;

class Poc
{
    private string $name;
    private User $obj;

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
     * @return \RavenDB\Client\Infrastructure\Entities\User
     */
    public function getObj(): User
    {
        return $this->obj;
    }

    /**
     * @param \RavenDB\Client\Infrastructure\Entities\User $obj
     */
    public function setObj(User $obj): void
    {
        $this->obj = $obj;
    }
}
