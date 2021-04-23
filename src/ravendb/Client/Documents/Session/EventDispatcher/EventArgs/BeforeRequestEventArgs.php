<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher\EventArgs;

use RavenDB\Client\Documents\Session\EventDispatcher\IEventHandler;

class BeforeRequestEventArgs implements IEventHandler
{
    private string $database;
    private string $url;
    private int $attemptNumber;
    private string $data;
    public function __construct(string $database, string $url, int $attemptNumber)
    {
        $this->database = $database;
        $this->url = $url;
        $this->attemptNumber = $attemptNumber;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getAttemptNumber(): int
    {
        return $this->attemptNumber;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): void
    {
        $this->data = $data;
    }
}
