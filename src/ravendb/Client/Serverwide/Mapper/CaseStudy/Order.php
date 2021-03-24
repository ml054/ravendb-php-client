<?php

namespace RavenDB\Client\Serverwide\Mapper\CaseStudy;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use RavenDB\Client\Serverwide\Mapper\Annotations\MyAnnotation;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Order {

    public DateTimeInterface $orderDate;
    public int $id;
    /**
     * @ORM\OneToMany(targetEntity=OrderLine::class, mappedBy="order")
     */
    private ?Collection $orderLines;

    public string $name;
    /** TODO : VALIDATE
     * @MyAnnotation(mapObject={
     *     "RavenDB\Client\Serverwide\Mapper\CaseStudy\OrderLine"
     *  }
     * )
     */
    private string $map;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }

    /**
     * @return Collection|OrderLine[]
     */
    public function getOrderLines(): Collection|OrderLine
    {
        return json_decode($this->orderLines->getValues());
    }

    public function addOderLine(OrderLine $orderLine):self
    {
        if (!$this->orderLines->contains($orderLine)) {
            $this->orderLines[] = $orderLine;
            $collection = new ArrayCollection($this->orderLines->toArray());
            $this->setOrderLines($collection);
        }
        return $this;
    }

    /**
     * @param ArrayCollection|Collection|null $orderLines
     */
    public function setOrderLines(ArrayCollection|Collection|null $orderLines): void
    {
        $this->orderLines = $orderLines;
    }

}