<?php

namespace RavenDB\Client\Util;

final class StringUtils
{
    public static function isBlank($string): bool
    {
        return empty($string);
    }

    public static function isNull($string): bool
    {
        return null === $string;
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

    public static function stripEnd($string,$char){
        return rtrim($string, $char);
    }

    public static function startWith($string, $needle): bool
    {
        return str_starts_with($string,$needle);
    }

    /**
     * @throws \Exception
     */
    public static function pascalize($content): array
    {
        if(!is_array($content) ||(is_array($content) && count($content) === 0)) throw new \Exception('Only Array is supported for now');
        $format = [];
        foreach($content as $key=>$data){
            $format[ucfirst($key)] = $data;
        }
        return $format;
    }
}
