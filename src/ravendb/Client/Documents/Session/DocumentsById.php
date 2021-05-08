<?php

namespace RavenDB\Client\Documents\Session;

use Doctrine\Common\Collections\ArrayCollection;

class DocumentsById
{
    private DocumentInfo|ArrayCollection $_inner;

    public function __construct()
    {
        $this->_inner = new ArrayCollection();
    }

    public function getValue(?string $id):?DocumentInfo {
        return $this->_inner->get($id);
    }

    public function add(DocumentInfo $info):void{
        if($this->_inner->containsKey($info->getId())) return;
        $this->_inner->set($info->getId(),$info);
    }

    public function remove (string $id):bool {
      //  dd("hereree");
        return $this->_inner->remove($id) !== null;
    }

    public function clear():void {
        $this->_inner->clear();
    }

    public function getCount():int{
        return $this->_inner->count();
    }

    /**
     * @throws \Exception
     */
    public function iteration(){
        return $this->_inner->getIterator();
    }
}
