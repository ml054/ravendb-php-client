<?php

namespace RavenDB\Client\Documents\Operations;

use RavenDB\Client\Documents\Session\IMetadataDictionary;

interface ICompareExchangeValue
{
    public function getKey():string;
    public function getIndex():string;
    public function setIndex(string $index):void;
    public function getValue():object;
    public function getMetadata():IMetadataDictionary;
    public function hasMetadata():bool;
}
