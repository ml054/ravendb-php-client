<?php

namespace RavenDB\Tests\Client\Entity;

class Person
{
    private $children = [
        'Wouter' => ["Test"],
    ];

    public function __get($id): array
    {
        return $this->children[$id];
    }
}
/*  TODO: SOURCE CODE REFERENCE(S) ( [] NODEJS | [] JAVA )
class Person {

}
* */