<?php

namespace RavenDB\Client\Documents\Session;

use Doctrine\Common\Collections\ArrayCollection;
use Swaggest\JsonDiff\JsonDiff;

class DocumentsByEntityUnitOfWork
{
    private ArrayCollection $_inner;

    public function __construct() {
        $this->_inner = new ArrayCollection();
    }

    public function tracker(object $entity,?JsonDiff $jsonDiff):void {
       // TODO
    }
}
