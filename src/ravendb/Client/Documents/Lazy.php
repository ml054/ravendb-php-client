<?php

namespace RavenDB\Client\Documents;
class Lazy
{
    private Supplier $valueFactory;

    private bool $value;

    public function __construct(Supplier $valueFactory)
    {
        $this->valueFactory = $valueFactory;
    }

    public function getValue(): bool
    {
        return $this->value;
    }
}
