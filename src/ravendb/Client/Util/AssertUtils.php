<?php

namespace RavenDB\Client\Util;

use PHPUnit\Framework\TestCase;
// TODO: to add further going more assertions
class AssertUtils
{
    static private array|string|object $elements;

    public static function assertThat(array|string|object $element): self
    {
        $encode = is_object($element) ? array($element) : $element;
        self::$elements = $encode;
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

    public static function hasSize(int $size)
    {
        TestCase::assertCount($size, static::$elements);
    }
}
