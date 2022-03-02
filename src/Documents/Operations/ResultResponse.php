<?php

namespace RavenDB\Documents\Operations;

use InvalidArgumentException;
use RavenDB\Type\TypedArrayInterface;

abstract class ResultResponse
{
    private string $type;

    private $results;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function setResults($results): void
    {
        if ((!$results instanceof $this->type)) {
            throw new InvalidArgumentException('Results must be instance of: ' . $this->type);
        }

        $this->results = $results;
    }
}
