<?php

namespace RavenDB\Client\Documents\Session;

use Exception;
use RavenDB\Client\Util\StringUtils;

class DocumentsChanges
{
    private object $fieldOldValue;
    private object $fieldNewValue;
    private $changeType;
    private $fieldName;
    private $fieldPath;
    private const CHANGE_TYPE = [
        "DOCUMENT_DELETED",
        "DOCUMENT_ADDED",
        "FIELD_CHANGED",
        "NEW_FIELD",
        "REMOVED_FIELD",
        "ARRAY_VALUE_CHANGED",
        "ARRAY_VALUE_ADDED",
        "ARRAY_VALUE_REMOVED"
    ];
    public function getFieldOldValue(): object { return $this->fieldOldValue; }
    public function setFieldOldValue(object $fieldOldValue): void { $this->fieldOldValue = $fieldOldValue; }
    public function getFieldNewValue(): object { return $this->fieldNewValue; }
    public function setFieldNewValue(object $fieldNewValue): void { $this->fieldNewValue = $fieldNewValue; }

    /**
     * @throws Exception
     */
    public static function changeType($type) {
        if(!in_array($type,self::CHANGE_TYPE)) throw new Exception("Unknown change type");
        return $type;
    }
    public function getFieldName() { return $this->fieldName; }
    public function setFieldName($fieldName): void { $this->fieldName = $fieldName; }
    public function getFieldPath() { return $this->fieldPath; }
    public function setFieldPath($fieldPath): void { $this->fieldPath = $fieldPath; }
    public function getFieldFullName(){
        return empty($this->fieldPath) ? $this->fieldName : $this->fieldPath.".".$this->fieldName;
    }

}
