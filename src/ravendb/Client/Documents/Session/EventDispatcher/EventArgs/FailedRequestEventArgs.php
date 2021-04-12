<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher\EventArgs;

use Exception;

class FailedRequestEventArgs
{
    private string $_database;
    private string $_url;
    private Exception $_exception;

    public function __construct(string $database, string $url, Exception $exception)
    {
        $this->_database = $database;
        $this->_url = $url;
        $this->_exception = $exception;
    }

    public function getDatabase(): string
    {
        return $this->_database;
    }

    public function getUrl(): string
    {
        return $this->_url;
    }

    public function getException(): Exception
    {
        return $this->_exception;
    }

}
