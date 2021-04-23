<?php /** @noinspection ALL */

namespace RavenDB\Client\Json;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\DataBind\Node\ObjectNode;
use RavenDB\Client\Documents\Session\IMetadataDictionary;

class MetadataAsDictionary  implements IMetadataDictionary
{
    private IMetadataDictionary|ArrayCollection $_parent;
    private string $_parentKey;
    private ArrayCollection $_metadata;
    private ObjectNode|ArrayCollection $_source; // TODO CHECK WITH TECH HOW TO IMPLEMENT SOURCE PROPERTY
    private bool $dirty = false;

    public function __construct(ObjectNode|ArrayCollection $metadata, IMetadataDictionary|ArrayCollection $parent, string $parentKey)
    {
        if(null === $parent) throw new \InvalidArgumentException("Parent cannot be null");
        if(null === $parentKey) throw new \InvalidArgumentException("ParentKey cannot be null");
        $this->_parent = $parent;
        $this->_parentKey = $parentKey;
    }


    public function isDirty()
    {
        return $this->dirty;
    }

    private function initialize(ObjectNode $metadata){
        $this->dirty = true;
        $_metadata = new ArrayCollection();
    }

    public function size():int {
        if(null !== $this->_metadata){
            return $this->_metadata->count();
        }
        return $this->_source->count();
    }

    public function put(string $key, object $value){
        if(null === $this->_metadata){
            $this->initialize($this->_source);
        }
        $this->dirty = true;
        return $this->_metadata->set($key,$value);
    }

    public function get($key)
    {
        if(null === $this->_metadata){
            return $this->_metadata->get($key);
        }
    }

    public function getObjects(array $key): IMetadataDictionary
    {
        // TODO: Implement getObjects() method.
    }

    public function getString(string $key)
    {
        // TODO: Implement getString() method.
    }

    public function getLong(string $key)
    {
        // TODO: Implement getLong() method.
    }

    public function getBoolean(bool $key): bool
    {
        // TODO: Implement getBoolean() method.
    }

    public function getDouble(string $key): float
    {
        // TODO: Implement getDouble() method.
    }

    public function getObject(string $key): IMetadataDictionary
    {
        // TODO: Implement getObject() method.
    }
}
