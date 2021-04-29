<?php

namespace RavenDB\Client\Documents\Session;

use Doctrine\Common\Collections\ArrayCollection;
use Ds\Map;

class EntityToJson
{
    private InMemoryDocumentSessionOperations $_session;
    /**
     * @psalm-var Map<Object, Map<String, Object>>
    */
    private Map $_missingDictionary;

    /**
     * All the listeners for this session
     * @param InMemoryDocumentSessionOperations $_session
     */
    public function __construct(InMemoryDocumentSessionOperations $_session)
    {
        $this->_session = $_session;
    }

    /**
     * @psalm-return Map<Object, Map<String, Object>>
     */
    public function getMissingDictionary(): Map
    {
        return $this->_missingDictionary;
    }



}
