<?php /** @noinspection ALL */

namespace RavenDB\Tests\Client;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Infrastructure\Entities\User;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\CrudEntities\Arr1;
use RavenDB\Tests\Client\CrudEntities\Arr2;
use RavenDB\Tests\Client\CrudEntities\Family;
use RavenDB\Tests\Client\CrudEntities\FamilyMembers;
use RavenDB\Tests\Client\CrudEntities\Member;
use RavenDB\Tests\Client\CrudEntities\Poc;

class CrudTest extends RemoteTestBase
{
    /**
     * @throws \Exception
     */
    public function testCrudCanUpdatePropertyFromNullToObject(){
        try{
            $store = $this->getDocumentStore();
            $options = (new SessionOptions())->setDatabase('new_db_1');
            try {
                $session = $store->openSession($options);
                $poc = new User();
                $poc->setName("aviv");
                $session->store($poc,"users/1");
                $session->saveChanges();

            } finally {
                $store->close();
            }
            try {
                $session = $store->openSession($options);
                /**
                 * @var User $user
                 */
                $user = $session->load(User::class,'users/1');
                $user->setName(null);
                $session->saveChanges();
            } finally {
                $store->close();
            }
            try {
                $session = $store->openSession($options);
                /**
                 * @var User $user
                 */
                $user = $session->load(User::class,'users/1');
                AssertUtils::assertThat($user->getName())::isNull();
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
            $options->setDatabase('new_db_1');
            try {
                /**
                 * @var FamilyMembers $newFamily
                 */
                /// TEST PART 1
                $session = $store->openSession($options);
                $member1 = (new Member())->setName("Hibernating Rhinos")->setAge(8);
                $member2 = (new Member())->setName("RavenDB")->setAge(4);
                $family = (new FamilyMembers())->setMembers([$member1,$member2]);
                $session->store($family,"family/1");
                $session->saveChanges();

                $member1 = (new Member())->setName("RavenDB")->setAge(4);
                $member2 = (new Member())->setName("Hibernating Rhinos")->setAge(8);
                $newFamily = $session->load(FamilyMembers::class,"family/1");
                $newFamily->setMembers([$member1,$member2]);
                /** ASSERTIONS TEST PART 1 */

                // TEST PART 2
                $member1 = (new Member())->setName("Toli")->setAge(5);
                $member2 = (new Member())->setName("Boki")->setAge(15);
                $newFamily->setMembers([$member1,$member2]);
                /** ASSERTIONS TEST PART 2 */

            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
    public function testCrudOperationsWithArrayInObject(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase('new_db_1');
            try {
                /**
                 * @var Family $newFamily
                 */
                $session = $store->openSession($options);
                $family = (new Family())->setNames(["Hibernating Rhinos", "RavenDB"]);
                $session->store($family,'family/1');

                $newFamily = $session->load(Family::class,'family/1');
                $newFamily->setNames(["Toli", "Mitzi", "Boki"]);
                $session->saveChanges();

            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
    public function testCrudOperationsWithArrayOfArrays(){
        try{

            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase('new_db_1');
            try {
                /**
                 * @var Arr2 $newArr
                 */
                $session = $store->openSession($options);
                $a1 = (new Arr1())->setStr(["a","b"]);
                $a2 = (new Arr1())->setStr(["c","d"]);
                $arr = (new Arr2)->setArr1([$a1,$a2]);
                $session->store($arr,"arr/1");
                $session->saveChanges();

                $newArr = $session->load(Arr2::class,"arr/1");
                $a1 = (new Arr1())->setStr(["d","c"]);
                $a2 = (new Arr1())->setStr(["a","b"]);
                $newArr->setArr1([$a1,$a2]);
                // ASSERTIONS HERE

            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
    public function testcrudOperationsWithNull(){
            try{
                $store = $this->getDocumentStore();
                $options = new SessionOptions();
                $options->setDatabase('new_db_1');
                try {
                   $session = $store->openSession($options);
                   $user = (new User())->setName(null);
                   $session->store($user,"users/1");
                   $session->saveChanges();

                   $user2 = $session->load(User::class,"users/1");
                   // ASSERTIONS
                } finally {
                    $store->close();
                }
            } finally {
                $store->close();
            }
        }
}