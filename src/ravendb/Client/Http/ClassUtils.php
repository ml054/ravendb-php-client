<?php

namespace RavenDB\Client\Http;

use InvalidArgumentException;
use ReflectionClass;

class ClassUtils
{
    const STATICINIT = "staticInit";
    // TODO: TO LAB MIGRATION REFLECTION -> CLOSURE
    public static function staticInit(object $provider)
    {
        if(!method_exists($provider,self::STATICINIT)){
            throw new InvalidArgumentException("Invalid provider or private static method ".self::STATICINIT." is not implemented");
        }
        $reflector = new ReflectionClass($provider);
        try {
            $method = $reflector->getMethod(self::STATICINIT);
            $method->setAccessible(true);
            $method->invoke($reflector);
        } catch (\ReflectionException $e) {
        }
    }
}
