<?php

namespace RavenDB\Client\Documents\Commands;

use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;

class HeadDocumentCommand extends RavenCommand
{
    private string $_id;
    private string $_changeVector;

    public function __construct(string $id, string $changeVector)
    {
        parent::__construct(null);
    }

    public function isReadRequest(): bool
    {
        // TODO: Implement isReadRequest() method.
    }

    public function createRequest(ServerNode $node): array|string|object
    {
        // TODO: Implement createRequest() method.
    }
}
