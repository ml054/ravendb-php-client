<?php /** @noinspection ALL */

namespace RavenDB\Tests\Client;

use phpDocumentor\Reflection\DocBlock\Description;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Infrastructure\Entities\User;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\CrudEntities\FamilyMembers;
use RavenDB\Tests\Client\CrudEntities\Member;
use RavenDB\Tests\Client\CrudEntities\Poc;
use function PHPUnit\Framework\assertThat;

class CrudTest extends RemoteTestBase
{
    // @Test - CrudTest */
    public function testCrudCanUpdatePropertyFromNullToObject(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            $options->setRequestExecutor($store->getRequestExecutor());

            try {
                $session = $store->openSession($options);
                $poc = new Poc();
                $poc->setName("aviv");
                $poc->setObj(null);
                $session->store($poc,"pocs/1");
                $session->saveChanges();
            } finally {
                $session->close();
            }

            // TO FIX IN TRACKENTITY : SUBMITTING NULL OBJECT
            try {
                $session = $store->openSession($options);
                 $poc = $session->load(Poc::class,"pocs/1");
                 $user = new User();
                 $poc->setObj($user);
                 $session->saveChanges();
            } finally {
                $session->close();
            }
            try {
                $session = $store->openSession($options);
                 /**
                   * @var Poc $poc
                  */
                 $poc = $session->load(Poc::class,"pocs/1");
                 AssertUtils::assertThat($poc->getObj())::isNull();
            } finally {
                $session->close();
            }
        } finally {
            $store->close();
        }
    }

    // @Test - CrudTest */
    public function testCrudOperationsWithArrayOfObjects(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            $options->setRequestExecutor($store->getRequestExecutor());
            try {
                $session = $store->openSession($options);
                $member1 = new Member();
                $member1->setName("Hibernating");
                $member1->setAge(4);

                $member2 = new Member();
                $member2->setName("RavenDB-new Entry");
                $member2->setAge(4);

                $family = new FamilyMembers();
                $family->setMembers([$member1,$member2]);
                $session->store($family,'family/2');
                $session->saveChanges();

                $member1 = new Member();
                $member1->setName("Hibernating");
                $member1->setAge(4);

                $member2 = new Member();
                $member2->setName("Rhinos");
                $member2->setAge(4);
                /**
                 * @var FamilyMembers $newFamily
                 */
                $newFamily = $session->load(FamilyMembers::class,"family/2");

                AssertUtils::assertThat($newFamily)::isObject();
                AssertUtils::assertThat($newFamily)::isInstanceOf(FamilyMembers::class);

            } finally {
                $session->close();
            }

        } finally {
            $store->close();
        }
    }
}