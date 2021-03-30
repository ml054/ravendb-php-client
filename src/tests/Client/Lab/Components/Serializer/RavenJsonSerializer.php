<?php
namespace RavenDB\Tests\Client\Lab\Components\Serializer;
use Doctrine\Common\Annotations\AnnotationReader;
use Exception;
use RavenDB\Tests\Client\Mapper\CombinedExtrator;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class JsonSerializerEncoder
 * @package RavenDB\Tests\Client\Lab\Components\Serializer
 * @see https://symfony.com/doc/current/components/serializer.html
 * @see https://symfony.com/doc/current/components/property_info.html#phpdocextractor
 */
class RavenJsonSerializer extends JsonEncoder
{
    /**
     * @return Serializer
     * using 2 merged class and property types for reverse reflection
     */
    public static function serializer(): Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
        $normalizer = new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter, null, new CombinedExtrator());
        $encoder = new JsonEncoder();
        return new Serializer([new ArrayDenormalizer(), $normalizer], [$encoder]);
    }

    /**
     * @param string $data
     * @param string $type
     * @return mixed
     * @throws Exception
     */
    public static function deserializeData(string $data,string $type){

        if(!is_string($data)){
            throw new Exception('Data source must be a valid string');
        }

        $serializer = self::serializer();
        return $serializer->deserialize($data, $type, "json");
    }
}
