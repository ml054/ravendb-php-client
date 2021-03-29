<?php

namespace RavenDB\Client\Serverwide\Mapper\CaseStudy;

use DateTimeInterface;

class Person
{
    private string $name;
    private int $age;
    private DateTimeInterface $createdAt;
    private PersonRole $role;
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
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return PersonRole
     */
    public function getRole(): PersonRole
    {
        return $this->role;
    }

    /**
     * @param PersonRole $role
     */
    public function setRole(PersonRole $role): void
    {
        $this->role = $role;
    }


}
