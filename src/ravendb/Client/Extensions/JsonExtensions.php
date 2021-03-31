<?php

namespace RavenDB\Client\Extensions;

use Doctrine\Common\Annotations\AnnotationReader;
use Exception;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

// TODO NO NEED TO EXTEND EXTERNALS
class JsonExtensions
{
    /// TODO IMPROVE
    public static function getDefaultMapper():self{
        return new self;
    }

    /**
     * @return Serializer
     * using 2 merged class and property types for reverse reflection
     */
    public static function serializer(): Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
        $typeExtractors = new CombinedExtrator();
        $normalizer = new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter, null, $typeExtractors);
        $encoders = [new JsonEncoder()];
        $normalizers = [new ArrayDenormalizer(), $normalizer];
        return new Serializer($normalizers,$encoders);
    }

    /**
     * @param string $data
     * @param string $type
     * @return mixed
     * @throws Exception
     */
    public static function readValue(string $data,string $type): mixed
    {
        if(!is_string($data)){
            throw new Exception('Data source must be a valid string');
        }
        return self::serializer()->deserialize($data, $type, "json");
    }

    public static function writeValueAsString(object $object): object
    {
        try {
            $extractor = ClassInfoExtractor::propertyExtractors();
        } catch (Exception $e) {
        }
        $className = $object::class;

        dd($extractor->getProperties($className));
    }
}
