<?php


namespace RavenDB\Client\Documents\Operations;

use InvalidArgumentException;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Http\ClusterRequestExecutor;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Serverwide\Operations\IServerOperation;

class ServerOperationExecutor implements Closable
{
    private String $_nodeTag;
    private DocumentStore $_store;
    private ClusterRequestExecutor $_requestExecutor;
    private ClusterRequestExecutor $_initialRequestExecutor;
    public function __construct(?DocumentStore $store=null, ?ClusterRequestExecutor $requestExecutor, ?ClusterRequestExecutor $initialRequestExecutor=null, ?ServerOperationExecutor $cache=null, ?string $nodeTag=null)
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

        /*
         * TODO: Check with Marcin if listener is to be added
        store.registerEvents(_requestExecutor);
        if (_nodeTag == null) {
            store.addAfterCloseListener((sender, event) -> _requestExecutor.close());
        }*/
    }

    public function send(IServerOperation|IVoidServerOperation $operation){
        if($operation instanceof IVoidServerOperation){
            $command = $operation->getCommand($this->_requestExecutor->getConventions());
            $this->_requestExecutor->execute($command);
        }
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}
