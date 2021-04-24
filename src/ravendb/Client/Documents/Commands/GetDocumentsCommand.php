<?php

namespace RavenDB\Client\Documents\Commands;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;

class GetDocumentsCommand extends RavenCommand
{
    private string $_id;
    private ?array $_includes=null;
    private ?bool $_metadataOnly;
    private ?int $_start;
    private ?int $_pageSize;

    public function __construct(?int $start,?int $pageSize)
    {
        parent::__construct([]);
        /*if(null === $id){
            throw new \InvalidArgumentException('id cannot be null');
        }*/
        $this->_start = $start;
        $this->_pageSize = $pageSize;
    }

    public function isReadRequest(): bool
    {
       return true;
    }

    public function createRequest(ServerNode $node): array|string|object
    {
        dd(__METHOD__);
        // TODO: Implement createRequest() method.
    }

    public function setResponse(array|string $response, bool $fromCache)
    {
        $this->result = "blabla";
    }
}
