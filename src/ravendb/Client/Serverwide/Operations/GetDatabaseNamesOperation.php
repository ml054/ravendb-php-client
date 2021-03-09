<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;

class GetDatabaseNamesOperation implements IServerOperation
{
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
     * @return GetDatabaseNamesCommand
     */
    public function getCommand(?DocumentConventions $conventions = null): GetDatabaseNamesCommand
    {
        return new GetDatabaseNamesCommand($this->_start, $this->_pageSize);
    }
}
