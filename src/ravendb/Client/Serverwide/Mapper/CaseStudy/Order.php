<?php

namespace RavenDB\Client\Serverwide\Mapper\CaseStudy;
use DateTimeInterface;
use RavenDB\Client\Serverwide\Mapper\Annotations\MyAnnotation;

class Order {
    public DateTimeInterface $orderDate;
    public int $id;
    public string $name;
    /** TODO : VALIDATE
     * @MyAnnotation(mapObject="RavenDB\Client\Serverwide\Mapper\CaseStudy\OrderLine")
     */
    private string $map;
}