<?php

namespace RavenDB\Client\Primitives;
/// CHANGING CLASS TO INTERFACE FOR PHP : AN INTERFACE CANNOT "EXTENDS" A CLASS BUT CAN EXTEND ANOTHER INTERFACE
interface EventArgs
{
// public final static VoidArgs EMPTY = new VoidArgs();
    public static function EMPTY():VoidArgs;
}
