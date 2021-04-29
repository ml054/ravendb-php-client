<?php

namespace RavenDB\Client\DataBind\Node;
// EMULATING JAVA'S JUST TO ACTIVATE THE QUERIES ON THE JSON SERIALIZED
use RavenDB\Client\Constants;

class ObjectNode
{
    private $document;
    private const ALLOWED_CONSTANT_QUERY = [
        Constants::METADATA_KEY,
        Constants::METADATA_ID,
        Constants::METADATA_CHANGE_VECTOR
    ];

    public function __construct(array $doc)
    {
        $this->document = $doc;
    }

    public function get(string $keySearch){
        if(in_array($keySearch,self::ALLOWED_CONSTANT_QUERY) && array_key_exists($keySearch,$this->document)){
            return $this->document[$keySearch];
        };
        return null;
    }

    /**
     * @return array
     */
    public function getNodeDocument(): array
    {
        return $this->document;
    }

    /**
     * @param array $document
     */
    public function setNodeDocument(array $document): void
    {
        $this->document = $document;
    }


}
