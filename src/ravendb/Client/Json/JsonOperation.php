<?php

namespace RavenDB\Client\Json;
use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Documents\Session\DocumentInfo;
use RavenDB\Client\Documents\Session\DocumentsChanges;
use RavenDB\Client\Constants;
class JsonOperation
{
    /**
     * @throws \Exception
     */
    public static function entityChanged(object $newObject, DocumentInfo $documentInfo, ArrayCollection $changes){
        $changes = null !== $changes ? new ArrayCollection() : null;
        if($documentInfo->isNewDocument() && null !== $documentInfo->getDocument()){
            // TODO compareJson
        }

        if(null === $changes){
            return true;
        }
        self::newChange(null,null,null,null,$changes,Constants::CHANGE_TYPE_DOCUMENT_ADDED);
    }

    /**
     * @throws \Exception
     */
    public static function newChange(string $fieldPath, string $name, object $newValue, object $oldValue, ArrayCollection $docChanges, string $change):void {
        /* TODO: CHECK WITH TECH SUPPORT
         * if (newValue instanceof NumericNode) {
            NumericNode node = (NumericNode) newValue;
            newValue = node.numberValue();
        }

        if (oldValue instanceof NumericNode) {
            NumericNode node = (NumericNode) oldValue;
            oldValue = node.numberValue();
        }
         * */
        $documentsChanges = new DocumentsChanges();
        $documentsChanges->setFieldName($name);
        $documentsChanges->setFieldNewValue($newValue);
        $documentsChanges->setFieldOldValue($oldValue);
        $documentsChanges->setChange($change);
        $documentsChanges->setFieldPath($fieldPath);
        $docChanges->add($documentsChanges);
    }
}
