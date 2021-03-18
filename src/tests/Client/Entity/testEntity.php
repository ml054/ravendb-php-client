<?php

namespace RavenDB\Tests\Client\Entity;

use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

class testEntity extends TestCase
{
    public function testEntity(){
        $person = new Person();

        $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
            ->disableExceptionOnInvalidPropertyPath()
            ->getPropertyAccessor();
        $value = $propertyAccessor->getValue($person, 'Wouter');
        dd($value);
    }
}
/*  TODO: SOURCE CODE REFERENCE(S) ( [] NODEJS | [] JAVA )
class testEntity {

}
* */