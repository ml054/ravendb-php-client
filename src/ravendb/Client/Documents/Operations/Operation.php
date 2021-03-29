<?php

namespace RavenDB\Client\Documents\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RequestExecutor;

class Operation
{
    private RequestExecutor $_requestExecutor;
    private DocumentConventions $_conventions;
    private string $_id;
    private ?string $_nodeTag;

    public function __construct(RequestExecutor $requestExecutor, mixed $changes, DocumentConventions $conventions, string $id, ?string $nodeTag=null)
    {
        $this->_requestExecutor = $requestExecutor;
        $this->_conventions = $conventions;
        $this->_id= $id;
        $this->_nodeTag = $nodeTag;
        new $this($requestExecutor,$changes,$conventions,$id,null);
    }


}
