<?php

namespace RavenDB\Client\Documents\Session;

use Doctrine\Common\Collections\ArrayCollection;
use Ds\Map;
use Ds\Set;
use Iterator;

class DeletedEntitiesHolder
{
    /**
     * @psalm-var Set<Object>
    */
    private ArrayCollection $_deletedEntities;

    /**
     * @psalm-var Set<Object>
     */
    private ArrayCollection $_onBeforeDeletedEntities;
    private bool $_prepareEntitiesDeletes;



    public function __construct()
    {
        $this->_deletedEntities = new ArrayCollection();
        $this->_onBeforeDeletedEntities = new ArrayCollection();
    }

    public function size():int {
        return $this->_deletedEntities->count() + ($this->_onBeforeDeletedEntities !== null ? $this->_onBeforeDeletedEntities->count(): 0);
    }

    public function add(object $entity){
        if($this->_prepareEntitiesDeletes){
            if(null === $this->_onBeforeDeletedEntities){
                $this->_onBeforeDeletedEntities = new ArrayCollection();
            }
            $this->_onBeforeDeletedEntities->add($entity);
            return;
        }
        $this->_deletedEntities->add($entity);
    }

    public function remove(object $entity):void {
        $this->_deletedEntities->remove($entity);
        if(null !== $this->_onBeforeDeletedEntities){
            $this->_onBeforeDeletedEntities->remove();
        }
    }

    public function evict(object $entity): void{
        if($this->_prepareEntitiesDeletes) throw new \InvalidArgumentException("Cannot Evict entity during OnBeforeDelete");
        $this->_deletedEntities->remove($entity);
    }

    public function contains(object $entity):bool {
        if($this->_deletedEntities->contains($entity)){
            return true;
        }

        if($this->_onBeforeDeletedEntities === null){
            return false;
        }
    }

    public function clear():void{
        $this->_deletedEntities->clear();
        if(null !== $this->_onBeforeDeletedEntities){
            $this->_onBeforeDeletedEntities->clear();
        }
    }

    public function iterator(): Iterator{
        // TODO
    }
}
