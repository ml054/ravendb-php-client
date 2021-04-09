<?php

namespace RavenDB\Client\Serverwide;

class DocumentsCompressionConfiguration
{
    private array $collections;
    private bool $compressRevisions;
    public function __construct(bool $compressRevisions, array $collections)
    {
        $this->compressRevisions = $compressRevisions;
        $this->collections = $collections;
    }

    /**
     * @return array
     */
    public function getCollections(): array
    {
        return $this->collections;
    }

    /**
     * @param array $collections
     */
    public function setCollections(array $collections): void
    {
        $this->collections = $collections;
    }

    /**
     * @return bool
     */
    public function isCompressRevisions(): bool
    {
        return $this->compressRevisions;
    }

    /**
     * @param bool $compressRevisions
     */
    public function setCompressRevisions(bool $compressRevisions): void
    {
        $this->compressRevisions = $compressRevisions;
    }



}
