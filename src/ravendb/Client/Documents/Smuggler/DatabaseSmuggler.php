<?php


namespace RavenDB\Client\Documents\Smuggler;


use RavenDB\Client\Documents\IDocumentStore;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Util\ObjectUtils;
class DatabaseSmuggler
{
    private IDocumentStore $_store;
    private String $_databaseName;
    private ?RequestExecutor $_requestExecutor;

    public function __construct(IDocumentStore $store, ?string $databaseName=null) {
       $this->_store = $store;
       $this->_databaseName = ObjectUtils::firstNonNull($databaseName,$store->getDatabase());
       if(null !== $this->_databaseName){
           $this->_requestExecutor = $store->getRequestExecutor($this->_databaseName);
       }else{
           $this->_requestExecutor = null;
       }
    }
}
