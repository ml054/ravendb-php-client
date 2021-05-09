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
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class CrudTest extends RemoteTestBase
{
    public function testCrudCanUpdatePropertyFromNullToObjectCanPersistOriginalAndOld(){
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
                    $session->store($poc,"pocs/2");
                    $session->saveChanges();
                } finally {
                    $store->close();
                }
                try {
                    $session = $store->openSession($options);
                    $poc = $session->load(Poc::class,"pocs/1");
                    dd($poc);
               //     $poc->setName("Raven");
               //    $session->saveChanges();
                } finally {
                    $store->close();
                }
            } finally {
                $store->close();
            }
    }

    public function testCrudOperationsWithArrayOfObjects(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            $options->setRequestExecutor($store->getRequestExecutor());
            try {
                $session = $store->openSession($options);
                $member1 = new Member();
                $member1->setName("Hibernating Rhinos");
                $member1->setAge(4);

                $member2 = new Member();
                $member2->setName("RavenDB");
                $member2->setAge(4);

                $family = new FamilyMembers();
                $family->setMembers([$member1,$member2]);
                $session->store($family,'family/1');
               // $session->saveChanges();

                $member1 = new Member();
                $member1->setName("RavenDB-testes");
                $member1->setAge(4);

                $member2 = new Member();
                $member2->setName("Hibernating Rhinos");
                $member2->setAge(4);
                /**
                 * @var FamilyMembers $newFamily
                 */
                $newFamily = $session->load(FamilyMembers::class,"family/1");
//                $newFamily->setMembers([$member1,$member2]);


            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
    public function testCrudOperationsWithArrayOfObjectsTest(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            $options->setRequestExecutor($store->getRequestExecutor());
            try {
                $session = $store->openSession($options);
                $member1 = new Member();
                $member1->setName("Hibernating Rhinosddd");
                $member1->setAge(4);

                $member2 = new Member();
                $member2->setName("RavenDdddddB");
                $member2->setAge(4);

                $family = new FamilyMembers();
                $family->setMembers([$member1,$member2]);
                $session->store($family,'family/1');
                // $session->saveChanges();

                $member1 = new Member();
                $member1->setName("RavenDB-testes");
                $member1->setAge(4);

                $member2 = new Member();
                $member2->setName("Hibernating Rhinos");
                $member2->setAge(4);
                /**
                 * @var FamilyMembers $newFamily
                 */
                $newFamily = $session->load(FamilyMembers::class,"family/1");
//                $newFamily->setMembers([$member1,$member2]);


            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }

}