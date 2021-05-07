<?php

namespace RavenDB\Client\Documents\Operations\CompareExchange;

use RavenDB\Client\Documents\Operations\ICompareExchangeValue;
use RavenDB\Client\Documents\Session\IMetadataDictionary;

class CompareExchangeValue implements ICompareExchangeValue
{
    public function getKey(): string
    {
        // TODO: Implement getKey() method.
    }

    public function getIndex(): string
    {
        // TODO: Implement getIndex() method.
    }

    public function setIndex(string $index): void
    {
        // TODO: Implement setIndex() method.
    }

    public function getValue(): object
    {
        // TODO: Implement getValue() method.
    }

    public function getMetadata(): IMetadataDictionary
    {
        // TODO: Implement getMetadata() method.
    }

    public function hasMetadata(): bool
    {
        // TODO: Implement hasMetadata() method.
    }
}
