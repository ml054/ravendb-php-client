<?php

namespace RavenDB\Client\Documents\Batches\Operations;
// DOCUMENT TYPE TEMPLATE return "Document" keyword for serializing purpuse
class Document
{
    private object $Document;
    private string $Type;
    private string $id;
    public function __construct(string $Type,string $id){
        $this->Type = $Type;
    }

    public function getType(): string{
        return $this->Type;
    }

    public function getDocument(): object{
        return $this->Document;
    }

    public function setDocument(object $Document): self {
        $this->Document = $Document;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }


}
