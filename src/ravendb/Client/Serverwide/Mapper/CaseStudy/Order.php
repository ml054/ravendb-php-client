<?php

namespace RavenDB\Client\Serverwide\Mapper\CaseStudy;
use Symfony\Component\Serializer\Annotation\SerializedName;

class Order {

    /**
     * @SerializedName("CustomId")
     */
    private int $id;
    private string $name;
    public \DateTimeInterface $orderDate;
    #[SerializedName("SingleItem")]
    private OrderLine $singleItem;
    /**
     * @SerializedName("ItemsArray")
     * @var OrderLine[]
     */
    private array $itemsArray;
    /**
     * @SerializedName("ItemsAsMap")
     * @var array<string,OrderLine>
     */
    private array $itemsAsMap;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return OrderLine
     */
    public function getSingleItem(): OrderLine
    {
        return $this->singleItem;
    }

    /**
     * @param OrderLine $singleItem
     */
    public function setSingleItem(OrderLine $singleItem): void
    {
        $this->singleItem = $singleItem;
    }

    /**
     * @return OrderLine[]
     */
    public function getItemsArray(): array
    {
        return $this->itemsArray;
    }

    /**
     * @param OrderLine[] $itemsArray
     */
    public function setItemsArray(array $itemsArray): void
    {
        $this->itemsArray = $itemsArray;
    }

    /**
     * @return OrderLine[]
     */
    public function getItemsAsMap(): array
    {
        return $this->itemsAsMap;
    }

    /**
     * @param OrderLine[] $itemsAsMap
     */
    public function setItemsAsMap(array $itemsAsMap): void
    {
        $this->itemsAsMap = $itemsAsMap;
    }

}