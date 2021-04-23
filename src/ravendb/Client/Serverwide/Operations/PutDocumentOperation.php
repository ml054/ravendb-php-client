<?php

namespace RavenDB\Client\Serverwide\Operations;

use RavenDB\Client\Documents\Commands\PutDocumentCommand;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;

class PutDocumentOperation implements IServerOperation
{
    private string $_id;
    private ?string $changeVectore=null;
    private object $_document;

    public function __construct(string $id, object $document, ?string $changeVectore=null)
    {
        $this->_id = $id;
        $this->_document = $document;
        $this->changeVectore = $changeVectore;
    }

    /**
     * @param DocumentConventions|null $conventions
     * @return RavenCommand
     */
    public function getCommand(?DocumentConventions $conventions = null): RavenCommand
    {
        return new PutDocumentCommand($this->_id,$this->_document,$this->changeVectore);
    }
}