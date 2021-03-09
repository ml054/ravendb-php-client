<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;

class GetDatabaseNamesOperation extends RavenCommand
{
    const OperationResultType =[ "OperationId", "CommandResult" , "PatchResult"];
    /**
     * GetDatabaseNamesOperation constructor.
     * @param int $_start
     * @param int $_pageSize
     */
    public function __construct(private int $_start, private int $_pageSize)
    {
        $this->_start = $_start;
        $this->_pageSize = $_pageSize;
    }

    /**
     * @param DocumentConventions|null $conventions
     * @return RavenCommand
     */
    public function getCommand(?DocumentConventions $conventions = null): RavenCommand
    {
        return new GetDatabaseNamesCommand($this->_start, $this->_pageSize);
    }

    public function isReadRequest(): bool
    {
        // TODO: Implement isReadRequest() method.
    }

    public function createRequest(ServerNode $node, &$url)
    {
        // TODO: Implement createRequest() method.

    }
}
