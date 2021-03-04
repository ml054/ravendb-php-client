<?php


namespace RavenDB\Client\Http;

abstract class VoidRavenCommand extends RavenCommand
{
    protected function __construct()
    {
        $this->responseType = RavenCommandResponseType::EMPTY;
    }

    public function isReadRequest(): bool {
        return false;
    }
}
