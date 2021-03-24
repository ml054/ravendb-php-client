<?php

namespace RavenDB\Tests\Client\Mapper;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use RavenDB\Client\Serverwide\Mapper\Annotations\AnnotationsMapper;
use RavenDB\Client\Serverwide\Mapper\Annotations\MyAnnotation;
use RavenDB\Client\Serverwide\Mapper\CaseStudy\Order;
use RavenDB\Client\Serverwide\Mapper\CaseStudy\OrderLine;
use RavenDB\Client\Serverwide\Mapper\ObjectMapper;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Client\Util\StringUtils;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorBuilder;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


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
    }

    public function testDeserialization() {
        $json = <<<'TAG'
{
            "OrderDate": "2021-03-19T16:17:52.4486295Z",
            "CustomId": 7,
            "Name": "test",
            "SingleItem": {
               "Id": "aaa",
               "comment": {
                  "text": "Is it working?"
               }
            }, 
            "itemsArray": [
                {
                   "Id": "bbb"
                },
                {
                   "Id": "ccc"
                }
            ],
            "ItemsAsMap": {
               "F1": {
                   "Id": "ddd"
                },
                "F2": {
                   "Id": "eee"
                }
            }
        }
TAG;

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $normalizer = new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter, null, new CombinedExtrator());
        $dateNormalizer = new DateTimeNormalizer();
        $encoder = new JsonEncoder();

        $serializer = new Serializer([$dateNormalizer, new ArrayDenormalizer(), $normalizer], [$encoder]);
        $result = $serializer->deserialize($json, Order::class, "json");
        dd($result);
    }
}
