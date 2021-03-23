<?php

namespace RavenDB\Client\Serverwide\Mapper\Annotations;
use Doctrine\Common\Annotations\Annotation;
/**
 * @Annotation
*/
class MyAnnotation
{
    public $myProperty; // TODO CONSIDER RELYING ON ANNOTATIONS TO CHECK WITH MARCIN
    public $mapObject; // TODO CONSIDER RELYING ON ANNOTATIONS TO CHECK WITH MARCIN
}