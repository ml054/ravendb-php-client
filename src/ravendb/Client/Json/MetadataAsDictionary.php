<?php /** @noinspection ALL */

namespace RavenDB\Client\Json;

use Doctrine\Common\Collections\ArrayCollection;
use Ds\Map;
use RavenDB\Client\DataBind\Node\ObjectNode;
use RavenDB\Client\Documents\Session\IMetadataDictionary;

class MetadataAsDictionary  implements IMetadataDictionary
{
    private IMetadataDictionary|Map $_parent;
    private string $_parentKey;
    private Map $_metadata;
    private ObjectNode|Map $_source;
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
        $_metadata = new Map();
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

    public function getObjects(string $key): IMetadataDictionary
    {
        $obj = $this->get($key);
        if(null !== $this->_metadata){
            return $this->_metadata->get($key);
        }
    }

    public function convertValue(string $key, object $value){
        if(null === $value){
            return null;
        }

        if(is_bool($value)){
            return (bool) $value;
        }

        if(is_int($value)){
            return (int) $value;
        }

        if(is_string($value)){
            return (string) $value;
        }

        if(is_object($value)){
            $dictionary = new MetadataAsDictionary($value,$this,$key);
            $dictionary->initialize((object) $value);
            return $dictionary;
        }

        if(is_array($value)){
            $array = $value;
            $arraySize = count($array);
            $result = (object) new Map($arraySize);
            for((int) $i; $i < $arraySize ; $i ++) {
                $result[$i] = $this->convertValue($key,$array->get(i));
            }
        }
    }

    public function getString(string $key)
    {
        $obj = $this->get($key);
        if(null === $obj){
            return null ; // TODO CHECK EQUIVALENT IN PHP SOURCE : return 0L;
        }
    }

    public function getLong(string $key)
    {

    }

    public function getBoolean(bool $key): bool
    {
        $obj = $this->get($key);
        if(null === $obj) return false;
        return (bool) $obj;
    }

    public function getDouble(string $key): float
    {
        // TODO: Implement getDouble() method.
    }

    public function getObject(string $key): ?IMetadataDictionary
    {
        $obj = $this->get($key);
        if(null === $obj){
            return null ;
        }
         return $obj;
    }
}
