<?php
namespace RavenDB\Client\Documents\Session;

use Ds\Map;

class DocumentsByEntityHolder
{
    /**
     * USING PSALM JAVA TO PHP FRIENDLY POPULAR ANNOTATIONS FOR REFERENCES.
     * THIS IS TO INSURE AT MAXIMUM DATA CONFIRMITY/INTEGRATY AND FOR GENERAL
     * MAPPING PURPOSE
     * @psalm-return Map<Object, DocumentInfo>
     */
    private Map $_onBeforeStoreDocumentsByEntity;

    /**
     * @psalm-return Map<Object, DocumentInfo>
     */
    private function _documentsByEntity() {
        return new Map();
    }

    private bool $_prepareEntitiesPuts = true;

    public function size(): int {
        return $this->_documentsByEntity()->count();
    }

    public function remove(object $entity):void {
        $this->_documentsByEntity()->remove($entity);
    }

    public function evict(object $entity):void {
        if($this->_prepareEntitiesPuts){

        }
    }
}
