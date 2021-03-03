<?php

namespace RavenDB\Client\Exceptions;


use RuntimeException;
use Throwable;

class RavenException extends RuntimeException
{
    private bool $reachedLeader;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function isReachedLeader(): bool
    {
        return $this->reachedLeader;
    }

    public static function generic(string $error, string $json): RavenException
    {
        return new RavenException($error . " Response: " . $json);
    }
}
