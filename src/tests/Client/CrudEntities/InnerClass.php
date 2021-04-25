<?php

namespace RavenDB\Tests\Client\CrudEntities;

class InnerClass
{
    private string $a;

    public function getA(): string
    {
        return $this->a;
    }
    public function setA(string $a): void
    {
        $this->a = $a;
    }

}
