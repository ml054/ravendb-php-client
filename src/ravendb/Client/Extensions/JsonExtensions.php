<?php

namespace RavenDB\Client\Extensions;

use Doctrine\Common\Annotations\AnnotationReader;
use Exception;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class JsonExtensions
 * @package RavenDB\Client\Extensions
 * @see https://symfony.com/doc/current/serializer.html
 * "Always make sure to load the DateTimeNormalizer when serializing the DateTime or
 * DateTimeImmutable classes to avoid excessive memory usage and exposing internal details."
 */
class JsonExtensions
{
    public static function getDefaultMapper():self{
        return new self;
    }

    /**
     * @return Serializer
     * GetSetMethodNormalizer consumes less than ObjectNormalizer in this specific scenario
     */
    public static function serializer(): Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
        $typeExtractors = new CombinedExtrator();
        $normalizer = new GetSetMethodNormalizer($classMetadataFactory,$metadataAwareNameConverter,$typeExtractors); // consume less then ObjectNormalizer
        $encoders = [new JsonEncoder()];
        $normalizers = [$normalizer,new DateTimeNormalizer()];
        return new Serializer($normalizers,$encoders);
    }

    /**
     * @param string $data
     * @param string $className
     * @return mixed
     * @throws Exception
     */
    public static function readValue(string $data,string $className): mixed
    {
        if(!is_string($data)){
            throw new Exception('Data source must be a valid json string');
        }
        return self::serializer()->deserialize($data, $className, "json");
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
