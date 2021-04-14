<?php

namespace RavenDB\Client\Util;

/**
 * Trait EventNameHolder
 * @package RavenDB\Client\Util
 * Traits are highly recommended in php over statics for performance concern
 */
trait EventNameHolder
{
    public $eventNameOnBeforeStore = "onBeforeStore";
    public $eventNameOnBeforeDelete = "onBeforeDelete";
    public $eventNameOnBeforeQuery = "onBeforeQuery";
    public $eventNameOnAfterSaveChanges = "onAfterSaveChanges";
    public $eventNameOnBeforeConversionToDocument = "onBeforeConversionToDocument";
    public $eventNameOnAfterConversionToDocument = "onAfterConversionToDocument";
    public $eventNameOnBeforeConversionToEntity = "onBeforeConversionToEntity";
    public $eventNameOnAfterConversionToEntity = "onAfterConversionToEntity";
}