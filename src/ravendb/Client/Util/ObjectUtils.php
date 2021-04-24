<?php
namespace RavenDB\Client\Util;

class ObjectUtils
{
    public static function firstNonNull(?array $getDatabases): ?string
    {
      $database = null;
      if(null === $getDatabases){
          return null;
      }
      foreach ($getDatabases as $database){
          if(null !== $database) break;
      }
      return $database;
    }
}