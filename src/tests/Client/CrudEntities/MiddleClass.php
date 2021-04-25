<?php

namespace RavenDB\Tests\Client\CrudEntities;

class MiddleClass
{
    private InnerClass $a;

    public function getA(): InnerClass
    {
        return $this->a;
    }
    public function setA(InnerClass $a): void
    {
        $this->a = $a;
    }
}
