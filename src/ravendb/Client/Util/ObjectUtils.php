<?php
namespace RavenDB\Client\Util;

class ObjectUtils
{
    public static function firstNonNull(array|object $getDatabases): string
    {
      $database = null;

      foreach ($getDatabases as $database){
          if(null !== $database) break;
      }
      return $database;
    }
}