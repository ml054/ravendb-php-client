<?php /** @noinspection ALL */

namespace RavenDB\Tests\Client;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Infrastructure\Entities\User;
use RavenDB\Client\Serverwide\Operations\CrudDatabaseOperation;
use RavenDB\Client\Serverwide\Operations\GetDatabaseNamesOperation;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\BatchCommand\Command;
use RavenDB\Tests\Client\BatchCommand\Operation\Document;
use RavenDB\Tests\Client\CrudEntities\Arr1;
use RavenDB\Tests\Client\CrudEntities\Arr2;
use RavenDB\Tests\Client\CrudEntities\Family;
use RavenDB\Tests\Client\CrudEntities\FamilyMembers;
use RavenDB\Tests\Client\CrudEntities\Member;
use RavenDB\Tests\Client\CrudEntities\Poc;

class CrudTestDatabase extends RemoteTestBase
{
    /**
     * @throws \Exception
     */
    public function testCanSaveChanges(){
        $store = $this->getDocumentStore();
        $conventions = new DocumentConventions();
        try{
            $executor = RequestExecutor::create($store->getUrls(), $store->getDatabase(), null, $conventions);
            try {
                $member1 = (new Member())->setName("Hibernating Rhinos")->setAge(8);
                $member2 = (new Member())->setName("RavenDB")->setAge(4);
                $members = [$member1,$member2];
                $family  = (new FamilyMembers())->setMembers($members);
                $serializer = JsonExtensions::storeSerializer();

                // wrapping in a batch command : ravendb server compliance for batch deployment. so far
                $newDocument = (new Document("PUT"))->setDocument($family);
                $command = (new Command())->setCommands([$newDocument]);
                dd($serializer->serialize($command,'json'));

                $operation = new CrudDatabaseOperation($family,'DemoDB');
                $command = $operation->getCommand($conventions);
                $executor->execute($command);
                } finally {
                    $store->close();
                }
            } finally {
                $store->close();
            }
        }
}