<?php
namespace RavenDB\Client\Documents\Session;
use Ds\Map;
class DocumentsByEntityHolder
{
    private Map $_inner;

    public function __construct()
    {
        $this->_inner = new Map();
    }

    public function size():int
    {
        return $this->_inner->count();
    }

    public function remove(object $entity):void
    {
        $this->_inner->remove($entity);
    }

    public function put(object $entity,DocumentInfo $documentInfo):void
    {
        if($this->_inner->hasKey($documentInfo->getId())) return;
        $this->_inner->put($entity,$documentInfo);
    }

    public function clear():void
    {
        $this->_inner->clear();
    }

    public function get(object $entity):?DocumentInfo
    {
        $documentInfo = $this->_inner->get($entity);
        if(null !== $documentInfo ){
            return $documentInfo;
        }
        return null;
    }

    /**
     * @throws \Exception
     */
    public function iterator(){
        return $this->_inner->getIterator();
    }
}
