<?php

namespace RavenDB\Client\Documents\Commands\Batches;

use RavenDB\Client\Documents\Batches\ICommandData;

class PutCommandDataBase implements ICommandData
{
    private string $id;
    private ?string $name = null;
    private object $document;
    private string $changeVector;
    private string $type = "PUT";
    public string $forceRevisionCreationStrategy="NONE";

    public function __construct(string $id, string $changeVector, object $document,string $strategy)
    {
        if ($document === null) {
            throw new \InvalidArgumentException("Document cannot be null");
        }
        $this->id = $id;
        $this->changeVector = $changeVector;
        $this->document = $document;
        $this->forceRevisionCreationStrategy = $strategy;
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getDocument(): object
    {
        return $this->document;
    }
    public function getType(): string
    {
        return $this->type;
    }
    /**
     * @return string
     */
    public function getChangeVector(): string
    {
        return $this->changeVector;
    }
}
