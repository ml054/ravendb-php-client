<?php /** @noinspection ALL */

namespace RavenDB\Tests\Client;
use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Documents\Batches\PutResult;
use RavenDB\Client\Documents\Commands\GetDocumentsCommand;
use RavenDB\Client\Documents\Commands\PutDocumentUpdateCommand;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Infrastructure\Entities\User;
use RavenDB\Client\Serverwide\Operations\GetDocumentsOperation;
use RavenDB\Client\Serverwide\Operations\PutDocumentOperation;
use RavenDB\Client\Serverwide\Operations\GetDatabaseNamesOperation;
use RavenDB\Client\Serverwide\Operations\PutDocumentUpdateOperation;
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
                $member1 = new Member();
                $member1->setName("Hibernating Rhinos")->setAge(8);
                $member1->setId("person/5");
                $member2 = (new Member())->setName("RavenDB")->setAge(4);
                $member2->setId("person/6");
                $members = [$member1,$member2];
                $family  = (new FamilyMembers())->setMembers($members);
                $serializer = JsonExtensions::storeSerializer();
                $newDocument = (new Document("PUT"))->setDocument($family);
                $command = (new Command())->setCommands([$newDocument]);
               // $request = $serializer->serialize($command,'json');
                $operation = new PutDocumentOperation('family/1',$command,null);
                $command = $operation->getCommand($conventions);
                $executor->execute($command);
                } finally {
                    $store->close();
                }
            } finally {
                $store->close();
            }
        }
    public function testCanPutDocument(){
        $store = $this->getDocumentStore();
        $conventions = new DocumentConventions();
        try{
            $executor = RequestExecutor::create($store->getUrls(), $store->getDatabase(), null, $conventions);
            try {
                $member1 = new Member();
                $member1->setName("Hibernating Rhinos")->setAge(8);
                $member1->setId("person/5");
                $member2 = (new Member())->setName("RavenDB")->setAge(4);
                $member2->setId("person/6");
                $members = [$member1,$member2];
                $family  = (new FamilyMembers())->setMembers($members);

                $operation = new PutDocumentOperation('family/2',$family,null);
                $command = $operation->getCommand($conventions);
                $executor->execute($command);
                /**
                 * @var PutResult $result
                */
                $result = $command->getResult();
                AssertUtils::assertThat($result)::isNotNull();
                AssertUtils::assertThat($result)::isObject();
                AssertUtils::assertThat($result->getId())::isIdenticalTo("family/2");
                AssertUtils::assertThat($result->getChangeVector())::isNotNull();
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
    public function testCanPutDocumentUpdate(){
        $store = $this->getDocumentStore();
        $conventions = new DocumentConventions();
        try{
            $executor = RequestExecutor::create($store->getUrls(), $store->getDatabase(), null, $conventions);
            try {
                $member1 = new Member();
                $member1->setName("Hibernating Rhinos")->setAge(8);
                $member1->setId("person/5");
                $member2 = (new Member())->setName("RavenDB")->setAge(4);
                $member2->setId("person/6");
                $members = [$member1,$member2];
                $family  = (new FamilyMembers())->setMembers($members);
                $operation = new PutDocumentUpdateOperation('family/2',$family,null);
                $command = $operation->getCommand($conventions);
                $executor->execute($command);
                $result = $command->getResult();
               // dd($result);
                /**
                 * @var PutResult $result
                 */
                $result = $command->getResult();
                AssertUtils::assertThat($result)::isNotNull();
                AssertUtils::assertThat($result)::isObject();
                AssertUtils::assertThat($result->getId())::isIdenticalTo("family/2");
                AssertUtils::assertThat($result->getChangeVector())::isNotNull();
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }

    public function testArrayCollectionSerializer(){
        $writeStartObject = new ArrayCollection();
        $writeStartObject->set("id","45");
        $writeStartObject->set("ChangeVector","A17-000000");
        $writeStartObject->set("Type","DELETE");
        $serializer = JsonExtensions::storeSerializer();
        $request = $serializer->serialize($writeStartObject,'json');
        dd($request);
    }

    public function testCanGetDocumentOperation(){

        $store = $this->getDocumentStore();
        $conventions = new DocumentConventions();
        try{
            $executor = RequestExecutor::create($store->getUrls(), $store->getDatabase(), null, $conventions);
            try {
                $operation = new GetDocumentsOperation('family/2',null,false,0,10);
                $command = $operation->getCommand($conventions);
                $executor->execute($command);
                $result = $command->getResult();
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }


}