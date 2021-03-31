<?php
namespace RavenDB\Tests\Client\Mapper;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use RavenDB\Client\Util\AssertUtils;
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
    public function testDeserialization() {
$json = <<<TAG
{
            "OrderDate": "2021-03-19T16:17:52.4486295Z",
            "CustomId": 7,
            "Name": "test",
            "SingleItem": {
               "Id": "aaa",
               "comment": "Is it working?"
               
            }, 
            "itemsArray": [
                {
                   "Id": "bbb"
                },
                {
                   "Id": "ccc"
                },
                {
                   "Id": "dddd"
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
        $normalizer = new ObjectNormalizer(null, $metadataAwareNameConverter, null, new CombinedExtrator());
        $dateNormalizer = new DateTimeNormalizer();
        $encoder = new JsonEncoder();
        $serializer = new Serializer([$dateNormalizer, new ArrayDenormalizer(), $normalizer], [$encoder]);
        $result = $serializer->deserialize($json, Order::class, "json");
        dd($result);
        AssertUtils::assertThat($result->getSingleItem())::isInstanceOf(Order::class); // TODO in progress
    }
}
