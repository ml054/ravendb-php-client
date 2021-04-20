<?php

namespace RavenDB\Tests\Client\BatchCommand\Operation;

class Document
{
    private object $Document;
    private string $Type;
    public function __construct(string $Type)
    {
        $this->Type = $Type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->Type;
    }


    /**
     * @return object
     */
    public function getDocument(): object
    {
        return $this->Document;

    }

    /**
     * @param object $Document
     * @return Document
     */
    public function setDocument(object $Document): self
    {
        $this->Document = $Document;
        return $this;
    }
}
