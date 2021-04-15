<?php

namespace RavenDB\Client\Documents\Session;
// SUBCLASS OF InMemory (lign 2299)
class DocumentsByEntityHolder
{
    private array $_documentsByEntity;
    private bool $_prepareEntitiesPuts;
    private array $_onBeforeStoreDocumentsByEntity;
    public function size(){
        return count($this->_documentsByEntity) + (null !== $this->_onBeforeStoreDocumentsByEntity ? count($this->_onBeforeStoreDocumentsByEntity) : 0);
    }

    public function remove(object $entity){
       // TODO implement unset
    }
}
