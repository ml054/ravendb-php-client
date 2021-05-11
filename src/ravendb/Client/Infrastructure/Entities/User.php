<?php

namespace RavenDB\Client\Infrastructure\Entities;

class User
{
    private ?string $name=null;
    private ?string $lastName=null;
    private ?string $addressId=null;
    private ?int $count=null;
    private ?int $age=null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name=null): void
    {
        $this->name = $name;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getAddressId(): ?string
    {
        return $this->addressId;
    }

    public function setAddressId(?string $addressId): void
    {
        $this->addressId = $addressId;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): void
    {
        $this->count = $count;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): void
    {
        $this->age = $age;
    }

}
