<?php


namespace RavenDB\Client;


use RavenDB\Client\Documents\IDocumentStore;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;

class DatabaseCommands implements Closable
{
    private IDocumentStore $_store;
    private RequestExecutor $_requestExecutor;
    private InMemoryDocumentSessionOperations $_session;

    public function __construct(IDocumentStore $store,string $databaseName)
    {
        if(null === $store){
            throw new \InvalidArgumentException('Store cannot be null');
        }
        $this->_store = $store;
        $this->_requestExecutor = $store->getRequestExecutor($databaseName);
    }

    public function getStore():IDocumentStore{
        return $this->_store;
    }

    public function getRequestExecutor(): RequestExecutor {
        return $this->_requestExecutor;
    }
    /*
     * TODO: TRYING THE EXTERNAL DB COMMAND ON THE EXECUTOR
     * */
    public function execute(RavenCommand $command):void{
        $this->_requestExecutor->execute($command);
    }


    public function close()
    {
        // TODO: Implement close() method.
    }
}

/*
 * public class DatabaseCommands implements CleanCloseable {


    public IDocumentStore getStore() {
        return _store;
    }

    public RequestExecutor getRequestExecutor() {
        return _requestExecutor;
    }

    public InMemoryDocumentSessionOperations getSession() {
        return _session;
    }

    public static DatabaseCommands forStore(IDocumentStore store) {
        return forStore(store, null);
    }

    public static DatabaseCommands forStore(IDocumentStore store, String databaseName) {
        return new DatabaseCommands(store, databaseName);
    }

}

 * */