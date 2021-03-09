<?php


namespace RavenDB\Client\Documents\Session;


class ResponseTimeItem
{
    private string $url;
    private int $duration;

    public function getUrl():string {
        return $this->url;
    }

    public function setUrl(string $url):void {
        $this->url = $url;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration):void {
        $this->duration = $duration;
    }
}