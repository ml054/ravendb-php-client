<?php

namespace RavenDB\Client\Documents\Session;

use Doctrine\Common\Collections\ArrayCollection;

class DeletedEntitiesHolder
{
    private ArrayCollection $deleteEntities;
    private ArrayCollection $_onBeforeDeletedEntities;
    private bool $_prepareEntitiesDeletes;
    private function isEmpty():bool{}
}
