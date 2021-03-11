<?php

namespace RavenDB\Client\Util;

use PHPUnit\Framework\TestCase;

class AssertUtils
{
    static private array|string $elements;

    public static function assertThat(array|string $element): self
    {
        self::$elements = $element;
        return new self;
    }

    public static function contains($item)
    {
        TestCase::assertContains($item, static::$elements);
    }
}