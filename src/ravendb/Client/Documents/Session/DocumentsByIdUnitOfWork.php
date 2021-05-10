<?php

namespace RavenDB\Client\Documents\Session;

use Doctrine\Common\Collections\ArrayCollection;
use Swaggest\JsonDiff\JsonDiff;

class DocumentsByIdUnitOfWork
{
    private ArrayCollection $_inner;

    public function __construct() {
        $this->_inner = new ArrayCollection();
    }

    public function tracker(string $id,?JsonDiff $jsonDiff):void {
        if(null !== $jsonDiff){
            $versions = [
                "orginal"=>$jsonDiff->getModifiedOriginal(),
                "new"=>$jsonDiff->getModifiedNew()
            ];
        }else{
            $versions = [];
        }

        $this->_inner->set($id,$versions);
    }
}
