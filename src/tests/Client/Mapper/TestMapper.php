<?php

namespace RavenDB\Tests\Client\Mapper;

use PHPUnit\Framework\TestCase;
use RavenDB\Client\Serverwide\Mapper\Annotations\MapperProperties;
use RavenDB\Client\Serverwide\Mapper\Annotations\MyAnnotation;
use RavenDB\Client\Serverwide\Mapper\CaseStudy\Order;
use RavenDB\Client\Serverwide\Mapper\CaseStudy\OrderLine;
use RavenDB\Client\Serverwide\Mapper\ObjectMapper;
use Doctrine\Common\Annotations\AnnotationReader;
use RavenDB\Client\Util\AssertUtils;
use ReflectionClass;

class TestMapper extends TestCase
{
    // TODO : CENTRALIZED ANNOTATION MAPPER RETURNING ARRAY OF DEPENDENCIES
    public function testMapper()
    {
        $reflectionClass = new ReflectionClass(Order::class);
        $property = $reflectionClass->getProperty('map');
        $reader = new AnnotationReader();
        $myAnnotation = $reader->getPropertyAnnotation($property, MyAnnotation::class); // Output : class as a string
        /// TODO : IMPROVE, ANNOTATION READER ALLOW THE ACCESS TO PROPERTY META INFORMATION THAT CAN BE INJECTED AND RETRIEVED
        AssertUtils::assertThat($myAnnotation->mapObject)::isNotEmpty();
    }
}
