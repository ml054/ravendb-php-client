<?php
namespace RavenDB\Tests\Client\Mapper;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\Lab\Models\Cart;
use RavenDB\Tests\Client\Lab\Models\CartItems;
use RavenDB\Tests\Client\RemoteTestBase;

/**
 * Class TestSerializer
 * @package RavenDB\Tests\Client\Lab
 * Purpose of the test is to create a generic serializer that takes json reponse and map it to an Object with reflection types.
 * output to match with object property. Using native php anotation style
 */
class TestSerializer extends RemoteTestBase
{
    public function testSerializerMapping(){
        $json = <<<EOD
        {
            "CardName":"MyCard",
            "DateOrder":"2021-03-19T16:17:52.4486295Z",
            "CardItems":{
                "id":"405a214a-a5d3-4e2f-b404-68e3a0aa1623"
            },
            "itemsCollection":[
                {
                    "Id":4,
                    "name":"My Item",
                    "price":"145"
                },
                {
                    "id":18,
                    "Name": "my Second Item",
                    "Price": "445"
                }
            ],
            "ItemsArrayMap": {
               "F1": {
                   "Id": "ddd",
                   "name": "test"
                },
                "F2": {
                   "Id": "eee"
                }
            }
        }
EOD;
        try {
            $result = JsonExtensions::readValue($json, Cart::class);

        } catch (\Exception $e) {
        }
        /**
         * @var Cart $result
        */
        AssertUtils::assertThat($result)::isNotNull();
        AssertUtils::assertThat($result)::isInstanceOf(Cart::class);
        AssertUtils::assertThat($result->getOrderedAt())::isNotNull();
        AssertUtils::assertThat($result->getOrderedAt())::isInstanceOf(\DateTime::class);
        AssertUtils::assertThat($result->getCardItems())::isObject();
        AssertUtils::assertThat($result->getCardItems())::isInstanceOf(CartItems::class);
        AssertUtils::assertThat($result->getItemsCollection())::isArray();
        AssertUtils::assertThat($result->getItemsCollection())::hasSize(2);
        AssertUtils::assertThat($result->getItemsCollection())::isArray();
        AssertUtils::assertThat($result->getItemsCollection())::hasSize(2);
    }
    public function testSemaphore(){
    }
}
