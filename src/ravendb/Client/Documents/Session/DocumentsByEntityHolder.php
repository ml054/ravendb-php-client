<?php

namespace RavenDB\Client\Documents\Session;
use Doctrine\Common\Collections\ArrayCollection;

class DocumentsByEntityHolder
{
    private ArrayCollection $_onBeforeStoreDocumentsByEntity;
    private bool $_prepareEntitiesPuts;
    private DocumentInfo|ArrayCollection|DocumentsByEntityHolder $_documentsByEntity;
    public function size():int{
        return $this->_documentsByEntity->count() + (null !== $this->_onBeforeStoreDocumentsByEntity ? count($this->_onBeforeStoreDocumentsByEntity) : 0);
    }
    /**
     * @throws \Exception
     */
    public function getDocumentsByEntity():DocumentInfo|ArrayCollection|DocumentsByEntityHolder {
        return $this->_documentsByEntity = new ArrayCollection();
    }

    /**
     * @throws \Exception
     */
    public function evict($entity):void {
        if($this->_prepareEntitiesPuts) throw new \Exception("Cannot Evict entity during OnBeforeStore");
        $this->getDocumentsByEntity()->remove($entity);
    }

    public function put(object $entity, DocumentInfo $documentInfo):void {
        if(!$this->_prepareEntitiesPuts){
            $this->getDocumentsByEntity()->set($entity,$documentInfo);
        }
    }

    public function prepareEntitiesPuts(){
        $this->_prepareEntitiesPuts = true;
    }

    public function get(object $entity):DocumentInfo{
        $documentInfo = $this->getDocumentsByEntity()->get($entity);
        if(null !== $documentInfo){
            return $documentInfo;
        }
    }

    public function iterator(): ArrayCollection {
        $firstIterator = $this->_documentsByEntity->getIterator();
        $secondIterator = $this->_documentsByEntity;
        $firstIteratorFunc = function () use ($firstIterator){
        };

        $secondIterator = $this->_inner;
        $secondIteratorFunc = function () use ($secondIterator){
        };
    }

    public function clear():void {
        $this->_documentsByEntity->clear();
    }
}
/*TODO : CHECK THE LEFT METHODS WITH TECH SUPPORT*/
/*
 *  @Override
        public Iterator<DocumentsByEntityEnumeratorResult> iterator() {
            Iterator<DocumentsByEntityEnumeratorResult> firstIterator
                    = Iterators.transform(_documentsByEntity.entrySet().iterator(),
                        x -> new DocumentsByEntityEnumeratorResult(x.getKey(), x.getValue(), true));

            if (_onBeforeStoreDocumentsByEntity == null) {
                return firstIterator;
            }

            Iterator<DocumentsByEntityEnumeratorResult> secondIterator
                    = Iterators.transform(_onBeforeStoreDocumentsByEntity.entrySet().iterator(),
                        x -> new DocumentsByEntityEnumeratorResult(x.getKey(), x.getValue(), false));

            return Iterators.concat(firstIterator, secondIterator);
        }

        @Override
        public Spliterator<DocumentsByEntityEnumeratorResult> spliterator() {
            return Spliterators.spliterator(iterator(), size(), Spliterator.ORDERED);
        }

*/