<?php

namespace RavenDB\Client\Documents\Commands\Batches;

class PutCommandDataWithJson extends PutCommandDataBase
{
    public function __construct(string $id, string $changeVector, object $document,string $strategy)
    {
        parent::__construct($id, $changeVector, $document,$strategy);
    }
}
