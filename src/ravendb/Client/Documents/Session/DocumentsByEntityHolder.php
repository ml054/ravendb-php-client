<?php

namespace RavenDB\Client\Documents\Session;
use Doctrine\Common\Collections\ArrayCollection;

class DocumentsByEntityHolder
{
    private ArrayCollection $_documentsByEntity;
    private ArrayCollection $_onBeforeStoreDocumentsByEntity;
    private bool $_prepareEntitiesPuts;

    public function size():int{
        return $this->_documentsByEntity->count() + (null !== $this->_onBeforeStoreDocumentsByEntity ? count($this->_onBeforeStoreDocumentsByEntity) : 0);
    }

    /**
     * @throws \Exception
     */
    public function evict($entity):void {
        if($this->_prepareEntitiesPuts) throw new \Exception("Cannot Evict entity during OnBeforeStore");
        $this->_documentsByEntity->remove($entity);
    }

    public function put(object $entity, DocumentInfo $documentInfo):void {
        if(!$this->_prepareEntitiesPuts){
            $this->_documentsByEntity->set($entity,$documentInfo);
            return;
        }
        $this->createOnBeforeStoreDocumentsByEntityIfNeeded();
        $this->_onBeforeStoreDocumentsByEntity->set($entity,$documentInfo);
    }

    private function createOnBeforeStoreDocumentsByEntityIfNeeded():void{
        if(null !== $this->_onBeforeStoreDocumentsByEntity){
            return;
        }
        $this->_onBeforeStoreDocumentsByEntity = new ArrayCollection();
    }
    public function prepareEntitiesPuts(){
        $this->_prepareEntitiesPuts = true;
    }


}
