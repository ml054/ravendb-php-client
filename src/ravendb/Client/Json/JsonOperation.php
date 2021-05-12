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
    public static function entityChanged(?object $newObject=null, ?DocumentInfo $documentInfo=null, ?ArrayCollection $changes=null){
       dd("Herere");
    }

    /**
     * @throws \Exception
     */
    public static function newChange(string $fieldPath, string $name, object $newValue, object $oldValue, ArrayCollection $docChanges, string $change):void {

        $documentsChanges = new DocumentsChanges();
        $documentsChanges->setFieldName($name);
        $documentsChanges->setFieldNewValue($newValue);
        $documentsChanges->setFieldOldValue($oldValue);
        $documentsChanges->setChange($change);
        $documentsChanges->setFieldPath($fieldPath);
        $docChanges->add($documentsChanges);
    }

    private static function compareJson(string $fieldPath, string $id, object $originalJson, object $newJson, ArrayCollection $changes, ArrayCollection $docChanges){

       $newJsonProps = new ArrayCollection();
       $oldJsonProps = new ArrayCollection();

    }
}
