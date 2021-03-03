<?php

namespace RavenDB\Client\Http;

use ReflectionClass;
// TODO : IT IS POSSIBLE TO EXTRACT VIA REFLECTION A PRIVATE METHODS AND MAKE IT PUBLIC (TEST)
// TODO : HOW TO INSTANTIATE A METHOD RETURN void WITH A NEW OBJECT IN THE BODY
// All private functions with return content or args injections work

class ClassUtils
{
    public static function staticInit()
    {
        $reflector = new ReflectionClass(RequestExecutor::class);
        $method = $reflector->getMethod('staticInit');
        $method->setAccessible(true);
        try {
            $method->invoke($reflector);
        } catch (\ReflectionException $e) {
        }
    }
}