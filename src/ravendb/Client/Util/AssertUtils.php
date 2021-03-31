<?php

namespace RavenDB\Client\Util;

use PHPUnit\Framework\TestCase;

class AssertUtils
{
    static private array|string|object $elements;

    public static function assertThat(array|string|object $element): self
    {
        self::$elements = $element;
        return new self;
    }

    public static function contains($item)
    {
        TestCase::assertContains($item, static::$elements);
    }

    public static function isNotNull()
    {
        TestCase::assertNotNull(static::$elements);
    }

    public static function isNotEmpty()
    {
        TestCase::assertNotEmpty(static::$elements);
    }

    public static function isArray()
    {
        TestCase::assertIsArray(static::$elements);
    }

    public static function isString()
    {
        TestCase::assertIsString(static::$elements);
    }

    public static function hasSize(int $size)
    {
        $param = is_object(static::$elements) ? (array) static::$elements : static::$elements;
        TestCase::assertCount($size, $param);
    }

    public static function isObject()
    {
        TestCase::assertIsObject(static::$elements);
    }

    public static function isInstanceOf(string $object)
    {
        TestCase::assertInstanceOf($object,static::$elements);
    }


    public static function databaseNotFoundError(){
        $jsonMessage = json_decode(static::$elements);
        // non case sensitive comparaison
        $assert = $jsonMessage->Type == "Error"
            && str_starts_with($jsonMessage->Message,'Database')
            && str_ends_with($jsonMessage->Message,'found')
        ;
        TestCase::assertTrue($assert,"The database does not exist");
    }
}
