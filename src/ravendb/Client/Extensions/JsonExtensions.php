<?php

namespace RavenDB\Client\Extensions;

use Doctrine\Common\Annotations\AnnotationReader;
use Exception;
use RavenDB\Client\Util\StringUtils;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
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
        $getsetNormalizer = new GetSetMethodNormalizer($classMetadataFactory,$metadataAwareNameConverter,$typeExtractors);
        $encoders = [new JsonEncoder()];
        $normalizers = [$getsetNormalizer,new DateTimeNormalizer(),new ObjectNormalizer()];
        return new Serializer($normalizers,$encoders);
    }

    public static function storeSerializer(){
        $encoders = [new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
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

    /**
     * Convert class properties in PascalCase and assign default values from the object if nothing submitted
     * @param object $object
     * @return string
     * @throws \Exception
     * Bug identified and corrected. Instantiation of the objet was wrong approach. Fixed
     */
    public static function writeValueAsString(object $object): string
    {
        $serializer = static::serializer();
        try {
            $normalize = StringUtils::pascalize($serializer->normalize($object));
        } catch (ExceptionInterface $e) {
            dd($e->getMessage());
        }
        return $serializer->encode($normalize,'json');
    }

    public static function writeValueAsString_previous(object $object,null|string $argument): string
    {
        $serializer = static::serializer();
        $record = new $object($argument);
        dd(__METHOD__,$object);
        try {
            $normalize = StringUtils::pascalize($serializer->normalize($record));
        } catch (ExceptionInterface $e) {
            dd($e->getMessage());
        }
        return $serializer->encode($normalize,'json');
    }
}
