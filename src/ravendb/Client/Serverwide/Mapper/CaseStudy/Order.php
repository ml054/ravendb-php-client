<?php

namespace RavenDB\Client\Serverwide\Mapper\CaseStudy;
use DateTimeInterface;
/**
 * @OderLine(className="OrderLine")
 */
class Order {
    public DateTimeInterface $orderDate;
    public int $id;
    public string $name;
    public OrderLine $singleItem;
    public array $itemsArray;
    public array $itemsAsMap;
}