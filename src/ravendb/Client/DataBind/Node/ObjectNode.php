<?php

namespace RavenDB\Client\DataBind\Node;
// EMULATING JAVA'S JUST TO ACTIVATE THE QUERIES ON THE JSON SERIALIZED
use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Constants;

class ObjectNode
{
    private ArrayCollection|array  $nodeDocument;
    private const ALLOWED_CONSTANT_QUERY = [
        Constants::METADATA_KEY,
        Constants::METADATA_ID,
        Constants::METADATA_CHANGE_VECTOR
    ];

    public function __construct(ArrayCollection|array $doc)
    {
        $this->nodeDocument = $doc;
    }

    public function get(string $keySearch){
        if(in_array($keySearch,self::ALLOWED_CONSTANT_QUERY) && array_key_exists($keySearch,$this->nodeDocument)){
            return $this->nodeDocument[$keySearch];
        };
        return null;
    }

    /**
     * @return array
     */
    public function getNodeDocument(): array
    {
        return $this->nodeDocument;
    }

    /**
     * @param array $document
     */
    public function setNodeDocument(array $document): void
    {
        $this->nodeDocument = $document;
    }


}
