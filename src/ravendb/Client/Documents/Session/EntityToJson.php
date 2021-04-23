<?php

namespace RavenDB\Client\Documents\Session;

use Doctrine\Common\Collections\ArrayCollection;

class EntityToJson
{
    private InMemoryDocumentSessionOperations $_session;
    private ArrayCollection $_missingDictionary;
    /**
     * All the listeners for this session
     * @param $_session
     */
    public function __construct(InMemoryDocumentSessionOperations $_session)
    {
        $this->_session = $_session;
    }

    /**
     * @return ArrayCollection
     */
    public function getMissingDictionary(): ArrayCollection
    {
        return $this->_missingDictionary;
    }



}
