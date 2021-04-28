<?php

namespace RavenDB\Client\Documents\Batches\Operations;
// DOCUMENT TYPE TEMPLATE return "Document" keyword for serializing purpuse
class Document
{
    private object $Document;
    private string $Type;
    public function __construct(string $Type){
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
}
