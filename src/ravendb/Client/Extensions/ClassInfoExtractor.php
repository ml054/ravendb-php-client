<?php

namespace RavenDB\Client\Extensions;
use Exception;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;

/**
 * Class PropertyInfoExtractor
 * @package RavenDB\Client\Extensions
 */
class ClassInfoExtractor extends PropertyInfoExtractor
{
    /**
     * @param $object
     * @throws Exception
     */
    public static function propertyExtractors(): PropertyInfoExtractor
    {
        $phpDocExtractor = new PhpDocExtractor();
        $reflectionExtractor = new ReflectionExtractor();

        $listExtractors         = [$reflectionExtractor];
        $typeExtractors         = [$phpDocExtractor, $reflectionExtractor];
        $descriptionExtractors  = [$phpDocExtractor];
        $accessExtractors       = [$reflectionExtractor];
        $propertyInitializableExtractors = [$reflectionExtractor];

        return new PropertyInfoExtractor(
            $listExtractors,
            $typeExtractors,
            $descriptionExtractors,
            $accessExtractors,
            $propertyInitializableExtractors
        );
    }
}
