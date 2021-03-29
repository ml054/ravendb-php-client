<?php
namespace RavenDB\Client\Util;

class ObjectUtils
{
    public static function firstNonNull(string $databaseName=null, array $getDatabases): ?string
    {
      $database = null;
      foreach ($getDatabases as $database){
          if(null !== $database) break;
      }
      return $database;
    }
}