<?php

namespace RavenDB\Client\Documents\Commands;

use RavenDB\Client\Documents\Batches\ICommandData;
use RavenDB\Client\Documents\Conventions\DocumentConventions;

class DeleteCommandData implements ICommandData
{
    private string $id;
    private string $name;
    private string $changeVector;

    public function __construct(string $id, string $changeVector)
    {
        if(null === $id) throw new \InvalidArgumentException('Id cannot be null');
        $this->changeVector = $changeVector;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getChangeVector(): string
    {
        return $this->changeVector;
    }

    public function serialize(DocumentConventions $conventions):void{
        // SERIALIZE FORMAT REQUEST
    }
}
