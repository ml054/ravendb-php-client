<?php

namespace RavenDB\Client\Documents\Session;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Primitives\Closable;

class DeletedEntitiesHolder
{
    private ArrayCollection $_deletedEntities;
    private ArrayCollection $_onBeforeDeletedEntities;
    private bool $_prepareEntitiesDeletes;
    private function isEmpty():bool{
        return $this->size() === 0 ?? false;
    }
    public function size():int{
        return $this->_deletedEntities->count() + (null !== $this->_onBeforeDeletedEntities ? count($this->_onBeforeDeletedEntities) : 0);
    }

    public function add(object $entity):void {
        if($this->_prepareEntitiesDeletes){
            if($this->_onBeforeDeletedEntities === null){
                $this->_onBeforeDeletedEntities = new ArrayCollection();
            }
            $this->_onBeforeDeletedEntities->add($entity);
            return;
        }
        $this->_deletedEntities->add($entity);
    }

    public function remove(object $entity):void {
        $this->_deletedEntities->remove($entity);
        if($this->_onBeforeDeletedEntities !== null){
            $this->_onBeforeDeletedEntities->remove($entity);
        }
    }

    /**
     * @throws \Exception
     */
    public function evict(object $entity):void {
        if($this->_prepareEntitiesDeletes){
            throw new \Exception("Cannot Evict entity during OnBeforeDelete");
        }
        $this->_deletedEntities->remove($entity);
    }

    public function contains(object $entity): bool {
        if($this->_deletedEntities->contains($entity)){
            return true;
        }

        if ($this->_onBeforeDeletedEntities === null) {
            return false;
        }

        return $this->_onBeforeDeletedEntities->contains($entity);
    }

    public function clear():void {
        $this->_deletedEntities->clear();
        if ($this->_onBeforeDeletedEntities != null) {
            $this-> _onBeforeDeletedEntities->clear();
        }
    }
    /**
     * @throws \Exception
     * TODO CHECK WITH TECH IF THIS IS CLOSURE TYPE OF IMPLEMENTATION IN JAVA
     */
    public function iterator(): ArrayCollection {
        $deletedIterator = $this->_deletedEntities->getIterator();
        $deletedIteratorFunc = function () use ($deletedIterator){
        };
    }

    public function prepareEntitiesDeletes():Closable{
        $this->_prepareEntitiesDeletes = true;
    }
}
