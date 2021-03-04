<?php


namespace RavenDB\Client\Documents\Operations;

use InvalidArgumentException;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Http\ClusterRequestExecutor;
use RavenDB\Client\Serverwide\Operations\IServerOperation;

class ServerOperationExecutor
{
    private String $_nodeTag;
    private DocumentStore $_store;
    private ClusterRequestExecutor $_requestExecutor;
    private ClusterRequestExecutor $_initialRequestExecutor;
    /// ConcurrentMap is build-in to java missing for now in the below constructor TODO: CHECK WITH MARCIN HOW TO IMPLEMENT Map APPROACH
    public function __construct(DocumentStore $store, ClusterRequestExecutor $requestExecutor, ClusterRequestExecutor $initialRequestExecutor, ServerOperationExecutor $cache, string $nodeTag)
    {
        if(null === $store){
           throw new InvalidArgumentException("Store cannot be null");
        }

        if(null === $requestExecutor){
            throw new InvalidArgumentException("RequestExecutor cannot be null");
        }

        $this->_store = $store;
        $this->_requestExecutor = $requestExecutor;
        $this->_initialRequestExecutor = $initialRequestExecutor;
        $this->_nodeTag = $nodeTag;
    }

    public function send(IServerOperation|IVoidServerOperation $operation){
        if($operation instanceof IVoidServerOperation){
            $command = $operation->getCommand($this->_requestExecutor->getConventions());
            $this->_requestExecutor->execute($command);
        }
    }
}
