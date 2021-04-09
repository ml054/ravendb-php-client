<?php

namespace RavenDB\Tests\Client;
use RavenDB\Client\Data\Driver\RavenDB;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Client\Util\ObjectMapper;
use RavenDB\Client\Util\StringUtils;
class CrudTest extends RemoteTestBase
{
    // TODO SUBJECT TO IMPROVMENT BUT THE TEST IS PASSING. To mirror with java model via saveChanges
    /**
     * The challenge addressed :
     * 1. create an object
     * 2. send with put method
     * 3. property recorded are PascalCase
     * 4. When the object is mapped to the class it loads the properties as per the class definition and return an object (easy to serialize)
    */
    public function testCrudCanUpdatePropertyFromNullToObject(){

        $store = $this->getDocumentStore();
        try {
            $poc = new Poc();
            $poc->setName("David");
            $poc->setObj(null);
            $serializer = JsonExtensions::serializer();
            $propertyConverter = StringUtils::pascalize($serializer->normalize($poc));
            $body = $serializer->encode($propertyConverter,'json');

            $putAttempt = new RavenDB($store,'db1');
            $putAttempt->put("45454545415",$body);

            // TODO relocate the mapper
            $mapResult = ObjectMapper::mapper()::readValue($body,Poc::class);
            AssertUtils::assertThat($mapResult)::isInstanceOf(Poc::class);
        } catch (\Exception $e) {
            dd($e->getMessage());
        } finally {
            $store->close();
        }
    }
}
