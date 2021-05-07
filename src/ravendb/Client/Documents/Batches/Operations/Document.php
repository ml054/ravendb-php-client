<?php

namespace RavenDB\Client\Documents\Batches\Operations;
// DOCUMENT TYPE TEMPLATE return "Document" keyword for serializing purpuse
use RavenDB\Client\Documents\Session\DocumentInfo;
use RavenDB\Client\Documents\Session\DocumentsByEntityEnumeratorResult;

class Document
{
    private object $Document;
    private string $Type;
    private string $Id;
    public function __construct(string $Type,string $Id){
        $this->Type = $Type;
        $this->Id = $Id;
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
        return $this->Id;
    }

    public function setId(string $id): void
    {
        $this->Id = $id;
    }


}
