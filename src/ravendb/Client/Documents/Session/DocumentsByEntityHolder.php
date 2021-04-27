<?php

namespace RavenDB\Client\Documents\Session;
use Doctrine\Common\Collections\ArrayCollection;

class DocumentsByEntityHolder
{
    private ArrayCollection $_inner;

    public function __construct()
    {
        $this->_inner = new ArrayCollection();
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
        if($this->_inner->containsKey($documentInfo->getId())) return;
        $this->_inner->set($entity,$documentInfo);
    }

    public function clear():void
    {
        $this->_inner->clear();
    }

    public function getValue(string $id):DocumentInfo
    {
        return $this->_inner->get($id);
    }

    public function get(object $entity):?DocumentInfo
    {
        $documentInfo = $this->_inner->get($entity);
        if(null !== $documentInfo ){
            return $documentInfo;
        }
        return null;
    }

    public function iterator(){
        return $this->_inner->getIterator();
    }
}
