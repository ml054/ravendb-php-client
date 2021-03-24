<?php

namespace RavenDB\Client\Serverwide\Mapper\CaseStudy;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

class Comment
{
    /**
     * @ORM\ManyToOne(targetEntity=OrderLine::class, inversedBy="orderLine")
     */
    private ?OrderLine $orderLine;

    /**
     * @return OrderLine|null
     */
    public function getOrderLine(): ?OrderLine
    {
        return $this->orderLine;
    }

    /**
     * @param OrderLine|null $orderLine
     */
    public function setOrderLine(?OrderLine $orderLine): void
    {
        $this->orderLine = $orderLine;
    }



}