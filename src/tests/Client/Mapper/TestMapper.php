<?php

namespace RavenDB\Tests\Client\Mapper;

use PHPUnit\Framework\TestCase;
use RavenDB\Client\Serverwide\Mapper\Annotations\AnnotationsMapper;
use RavenDB\Client\Serverwide\Mapper\Annotations\MyAnnotation;
use RavenDB\Client\Serverwide\Mapper\CaseStudy\Order;
use RavenDB\Client\Serverwide\Mapper\CaseStudy\OrderLine;
use RavenDB\Client\Serverwide\Mapper\ObjectMapper;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;

class TestMapper extends TestCase
{
    // TODO : CENTRALIZED ANNOTATION MAPPER RETURNING ARRAY OF DEPENDENCIES
    public function testMapper()
    {
        $objectMapper = new ObjectMapper();
        $order = new Order();
        $orderLine = new OrderLine();
        $reflectionClass = new ReflectionClass(AnnotationsMapper::class);
        $property = $reflectionClass->getProperty('myProperty');
        $reader = new AnnotationReader();
        $myAnnotation = $reader->getPropertyAnnotation($property, MyAnnotation::class);
        dd($myAnnotation->myProperty);
    }
}
