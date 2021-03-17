<?php

namespace RavenDB\Client\Util;

class NameUtils
{
    static private array|string|object $elements;

    public static function Names(array|string|object $element): self
    {
        self::$elements = $element;
        return new self;
    }

    public static function has($item)
    {
        TestCase::assertContains($item, static::$elements);
    }
}