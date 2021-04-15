<?php

namespace RavenDB\Tests\Client\CrudEntities;

class Arr1
{
    private array $str;
    public function getStr(): array
    {
        return $this->str;
    }
    public function setStr(array $str): void
    {
        $this->str = $str;
    }
}
