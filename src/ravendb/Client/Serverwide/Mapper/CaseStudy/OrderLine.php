<?php

namespace RavenDB\Client\Serverwide\Mapper\CaseStudy;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

class OrderLine {
    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="order")
     */
    private ?Order $order;
    public string $id;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="orderLine")
     */
    private ?Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();

    }

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

    /**
     * @return Collection|OrderLine[]
     */
    public function getComments(): Collection|Comment
    {
        return $this->comments->getValues();
    }

    public function addComment(Comment $comment):self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $collection = new ArrayCollection($this->comments->toArray());
            $this->setComment($collection);
        }
        return $this;
    }

    /**
     * @param ArrayCollection|Collection|null $comment
     */
    public function setComment(ArrayCollection|Collection|null $comment): void
    {
        $this->comments = $comment;
    }
}
