<?php

namespace RavenDB\Client\Documents\Operations\CompareExchange;

use RavenDB\Client\Documents\Operations\ICompareExchangeValue;
use RavenDB\Client\Documents\Session\IMetadataDictionary;
use RavenDB\Client\Json\MetadataAsDictionary;

class CompareExchangeSessionValue
{
    private string $_key;
    private string $_index;
    private ICompareExchangeValue $_value;
    private $_state;

    public function __construct(string $key, string $index, IMetadataDictionary $metadata,$state){
        if(null === $key) throw new \InvalidArgumentException("Key cannot be null");

            $this->_key = $key;
            $this->_index = $index;
            $this->_state = $state;
    }

}
