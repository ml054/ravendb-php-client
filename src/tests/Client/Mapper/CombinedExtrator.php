<?php

namespace RavenDB\Tests\Client\Mapper;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;

/**
 * Class CombinedExtrator
 * @package RavenDB\Tests\Client\Mapper
 * Combined with php extractor to access the phpDocumentor "collection" annotation type
 * (e.g. @var SomeClass<DateTime>, @var SomeClass<integer,string>, @var Doctrine\Common\Collections\Collection<App\Entity\SomeEntity>, etc.)
 */
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