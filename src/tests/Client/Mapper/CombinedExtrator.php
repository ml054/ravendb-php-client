<?php


namespace RavenDB\Tests\Client\Mapper;


use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

class CombinedExtrator implements PropertyTypeExtractorInterface
{

    private ReflectionExtractor $reflectionExtractor;
    private PhpDocExtractor $phpDocExtractor;
    /**
     * CombinedExtractor constructor.
     */
    public function __construct()
    {
        $this->reflectionExtractor = new ReflectionExtractor();
        $this->phpDocExtractor = new PhpDocExtractor();
    }

    public function getTypes(string $class, string $property, array $context = [])
    {
        $phpDocTypes = $this->phpDocExtractor->getTypes($class, $property, $context) ?? [];
        $reflectionTypes = $this->reflectionExtractor->getTypes($class, $property, $context) ?? [];

        if (count($phpDocTypes) === 0 && count($reflectionTypes) === 0) {
            return null;
        }

        return array_merge($phpDocTypes, $reflectionTypes);
    }
}