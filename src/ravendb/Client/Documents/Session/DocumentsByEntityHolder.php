<?php
namespace RavenDB\Client\Documents\Session;

use Ds\Map;

class DocumentsByEntityHolder
{
    /**
     * USING PSALM JAVA TO PHP FRIENDLY POPULAR ANNOTATIONS FOR REFERENCES.
     * THIS IS TO ENSURE AT MAXIMUM DATA CONFORMITY/INTEGRATY AND FOR GENERAL
     * MAPPING PURPOSE
     * @psalm-return Map<Object, DocumentInfo>
     */
    private Map $_inner;

    public function __construct()
    {
        $this->_inner = new Map();
    }

    public function size(): int {
        return $this->_inner->count();
    }

    public function remove(object $entity):void {
        $this->_inner->remove($entity);
    }

    public function put($entity, DocumentInfo $documentInfo): void {
            $this->_inner->put($entity,$documentInfo);
    }

    public function clear ():void {
        $this->_inner->clear();
    }

    public function get(object $entity): DocumentInfo {
        $documentInfo = $this->_inner->get($entity);
        if(null !== $documentInfo){
            return $documentInfo;
        }
    }

    /**
     * TODO implement
     * @psalm-return \Iterator<DocumentsByEntityEnumeratorResult>
     */
    public function iterator(){

    }

}
