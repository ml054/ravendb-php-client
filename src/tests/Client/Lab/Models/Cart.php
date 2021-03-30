<?php

namespace RavenDB\Tests\Client\Lab\Models;
use DateTime;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Class Card
 * @package RavenDB\Tests\Client\Lab\Models
 * Goal project required properties in a reflection/annotation flow keeping php standards : set/getters
 * TODO: IMPLEMENT THE SCOPE
 */
class Cart
{
    #[SerializedName("CardName")]
    private string $name;

    #[SerializedName("DateOrder")]
    private DateTime $orderedAt;

    #[SerializedName("CardItems")]
    private CartItems $cardItems;

    /**
     * @var CartItems[]
     */
    #[SerializedName("ItemsCollection")]
    private array $itemsCollection;

    /**
     * phpDocumentor syntax to extract property types as collection
     * @see https://symfony.com/doc/current/components/property_info.html#type-getcollectionkeytype-type-getcollectionvaluetype
     * @var array<string,CartItems>
     */
    #[SerializedName("ItemsArrayMap")]
    private array $itemsArrayMap;

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
     * @return DateTime
     */
    public function getOrderedAt(): DateTime
    {
        return $this->orderedAt;
    }

    /**
     * @param DateTime $orderedAt
     */
    public function setOrderedAt(DateTime $orderedAt): void
    {
        $this->orderedAt = $orderedAt;
    }

    /**
     * @return CartItems
     */
    public function getCardItems(): CartItems
    {
        return $this->cardItems;
    }

    /**
     * @param CartItems $cardItems
     */
    public function setCardItems(CartItems $cardItems): void
    {
        $this->cardItems = $cardItems;
    }

    /**
     * @return CartItems[]
     */
    public function getItemsCollection(): array
    {
        return $this->itemsCollection;
    }

    /**
     * @param CartItems[] $itemsCollection
     */
    public function setItemsCollection(array $itemsCollection): void
    {
        $this->itemsCollection = $itemsCollection;
    }

    /**
     * @return CartItems[]
     */
    public function getItemsArrayMap(): array
    {
        return $this->itemsArrayMap;
    }

    /**
     * @param CartItems[] $itemsArrayMap
     */
    public function setItemsArrayMap(array $itemsArrayMap): void
    {
        $this->itemsArrayMap = $itemsArrayMap;
    }


}
