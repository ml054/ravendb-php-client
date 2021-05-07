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

    public function get(object $entity): object
    {
        $documentInfo = $entity;
        if(null !== $documentInfo){
            return $documentInfo;
        }
    }

    /**
     * TODO CHECK WITH TECH THIS IS ONLY FOR THE PURPOSE OR REACH THE BATCH NODE
     * @template TKey
     * @template TValue
     * @psalm-return array<TKey, DocumentsByEntityEnumeratorResult>
     */
    private function data(){
        return $this->_inner->pairs()->toArray();
    }

    /**
     * @throws \Exception
     */
    public function entities(){
        if(null === $this->data()) throw new \Exception("No data provided");
        $values = $this->_inner->values()->toArray();
        $keys = $this->_inner->keys()->toArray();
        $entities =[];
        foreach($values as $index=>$object){
            (object)$documentResult = new DocumentsByEntityEnumeratorResult($keys[0],$object,false);
            $entities[] = $documentResult;
        }
        return $entities;
    }
    /**
     * @psalm-return \Iterator<DocumentsByEntityEnumeratorResult>
     */
    public function iterator(){
    }
}
