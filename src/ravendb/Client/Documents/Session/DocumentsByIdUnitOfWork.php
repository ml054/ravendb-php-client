<?php

namespace RavenDB\Client\Documents\Session;

use Doctrine\Common\Collections\ArrayCollection;

class DocumentsByIdUnitOfWork
{
    private DocumentInfo|ArrayCollection $_inner;

    public function __construct()
    {
        $this->_inner = new ArrayCollection();
    }

    /**
     * @throws \Exception
     */
    public function associate(string $id, string $original, string $newVersion):void{
        if(null === $id) throw new \Exception("Document ID cannot be null ");

    }

    public function remove(string $id):bool {
        return $this->_inner->remove($id) !== null;
    }

    public function getCount():int{
        return $this->_inner->count();
    }

}
