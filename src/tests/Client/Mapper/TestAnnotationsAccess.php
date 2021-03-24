<?php

namespace RavenDB\Tests\Client\Mapper;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use RavenDB\Client\Serverwide\Mapper\Annotations\MyAnnotation;
use RavenDB\Client\Serverwide\Mapper\CaseStudy\Order;
use RavenDB\Client\Util\AssertUtils;

// TODO : GOAL ACCESS ANNOTATION OF A MAIN CLASS AND RETRIEVE DEPENDENCIES OBJECT / OR DO DATA FORMATING
class TestAnnotationsAccess extends TestCase
{
    public function testAnnotations(){
        $reflectionClass = new \ReflectionClass(Order::class);
        // Annotations are mapped to class property (in this case). In Order Entity, mapObject is assigned to the map property via annotations
        // and can be retrieved
        $property = $reflectionClass->getProperty('map');
        $reader = new AnnotationReader();
        $annotation = $reader->getPropertyAnnotation($property, MyAnnotation::class); // Output : collection of dependencies as array. Subject to validation
        AssertUtils::assertThat($annotation->mapObject)::isNotEmpty();
        // TODO CHECK WITH MARCIN RETURNING ARRAY BECAUSE COLLECTING ALL DEPENDENCIES TO INSTANTIATE
        AssertUtils::assertThat($annotation->mapObject)::isArray();
    }
}
