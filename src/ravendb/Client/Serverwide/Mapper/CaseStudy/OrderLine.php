<?php

namespace RavenDB\Client\Serverwide\Mapper\CaseStudy;

use Doctrine\ORM\Mapping as ORM;

class OrderLine {
    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="order")
     */
    private ?Order $order;
    public string $id;

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @param Order|null $order
     */
    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }

}
