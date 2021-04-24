<?php

namespace RavenDB\Client\Serverwide\Operations;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Documents\Commands\GetDocumentsCommand;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;

class GetDocumentsOperation implements IServerOperation
{
    private string $_id;
    private ?array $_includes=null;
    private ?bool $_metadataOnly;
    private ?int $_start;
    private ?int $_pageSize;
    public function __construct(string $_id, ?array $_includes, ?bool $_metadataOnly,int $_start, int $_pageSize)
    {
        $this->_id = $_id;
        $this->_includes = $_includes;
        $this->_metadataOnly = $_metadataOnly;
        $this->_start = $_start;
        $this->_pageSize = $_pageSize;
    }

    public function getCommand(DocumentConventions $conventions): RavenCommand
    {
        return new GetDocumentsCommand($this->_id,$this->_includes,$this->_metadataOnly,$this->_start,$this->_pageSize);
    }
}
