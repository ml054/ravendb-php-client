<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher\EventArgs;

use RavenDB\Client\Documents\Session\EventDispatcher\IEventHandler;

class BeforeRequestEventArgs implements IEventHandler
{
    private string $database;
    private string $url;
    private int $attemptNumber;
    public function __construct(string $database, string $url, int $attemptNumber)
    {
        $this->database = $database;
        $this->url = $url;
        $this->attemptNumber = $attemptNumber;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getAttemptNumber(): int
    {
        return $this->attemptNumber;
    }
}
