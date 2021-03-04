<?php

namespace RavenDB\Client\Util;

final class StringUtils
{
    public static function isBlank($string): bool
    {
        return empty($string);
    }

    public static function isNotBlank($string): bool
    {
        return !empty($string);
    }

    public static function equalsIgnoreCase(string $var1, string $var2): bool
    {
        return (strcasecmp($var1, $var2) == 0) ?? false;
    }

    public static function isString($string){
        return self::isNotBlank($string) && is_string($string);
    }

    public static function isArray($string){
        return is_array($string) && count($string) > 0;
    }
}
