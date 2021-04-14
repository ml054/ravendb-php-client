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
}