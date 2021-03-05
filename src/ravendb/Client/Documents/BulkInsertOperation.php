<?php


namespace RavenDB\Client\Documents;


use RavenDB\Client\Documents\Identity\GenerateEntityIdOnTheClient;
use RavenDB\Client\Primitives\Closable;

class BulkInsertOperation implements Closable
{
    private GenerateEntityIdOnTheClient $_generateEntityIdOnTheClient;

    public function close()
    {
        // TODO: Implement close() method. This method is as of now empty
    }
}