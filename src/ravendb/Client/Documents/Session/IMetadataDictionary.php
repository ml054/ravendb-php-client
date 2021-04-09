<?php

namespace RavenDB\Client\Documents\Session;

interface IMetadataDictionary
{
public function isDirty();

public function getObjects(array $key):IMetadataDictionary;

public function getString(string $key);

public function getLong(string $key);

public function getBoolean(bool $key):bool;

public function getDouble(string $key):float;

public function getObject(string $key):IMetadataDictionary;
}
