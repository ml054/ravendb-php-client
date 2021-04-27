<?php /** @noinspection ALL */

namespace RavenDB\Tests\Client;
use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Infrastructure\Entities\User;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\CrudEntities\Arr1;
use RavenDB\Tests\Client\CrudEntities\Arr2;
use RavenDB\Tests\Client\CrudEntities\Family;
use RavenDB\Tests\Client\CrudEntities\FamilyMembers;
use RavenDB\Tests\Client\CrudEntities\Member;
use RavenDB\Tests\Client\CrudEntities\Poc;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class CrudTest extends RemoteTestBase
{
    /**
     * @throws \Exception
     */

    public function testCanCrudCanUpdatePropertyToNull(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            $options->setRequestExecutor($store->getRequestExecutor());
            try {

                $session = $store->openSession($options);
                $poc = new User();
                $poc->setName("John");
                $session->store($poc,"users/2");
                $session->saveChanges();

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
            $options->setRequestExecutor($store->getRequestExecutor());
            $options->setDatabase($store->getDatabase());
            try {
                /**
                 * @var Family $newFamily
                 */
                $session = $store->openSession($options);
                $family = (new Family())->setNames(["Hibernating Rhinos", "RavenDB"])->setId("family/1");
                $session->store($family,$family->getId());
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
            $options->setDatabase($store->getDatabase());
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
    public function testCrudOperationsWithNull(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            try {
                $session = $store->openSession($options);
                $user = (new User())->setName(null);
                $session->store($user,"users/1",'Vec');
              //  $session->saveChanges();
              //  $user2 = $session->load(User::class,"users/1");
                // ASSERTIONS
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
    public function testCrudCanUpdatePropertyToNull(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            $options->setRequestExecutor($store->getRequestExecutor());

            try {
                $session = $store->openSession($options);
                $user1 = new User();
                $user1->setName("test");
                $user1->setId("user/1");
                $session->store($user1,"user/1","A:148");

                $user2 = new User();
                $user2->setId('user/2');
                $user2->setName("Clovis");
                $session->store($user2,"user/2","A:147");

                dd($session);
              //  $session->saveChanges();
            } finally {
                $store->close();
            }

            /*try {
                $session = $store->openSession($options);
                $user = $session->load(User::class,"users/1");
                $user->setName("Nemo");
                $session->saveChanges();
            } finally {
                $store->close();
            }

            try {
                $session = $store->openSession($options);
                $user = $session->load(User::class,"users/1");
                AssertUtils::assertThat($user->getName())::isNull();
            } finally {
                $store->close();
            }*/
        } finally {
            $store->close();
        }
    }
    public function testCrudOperationsWithArrayInObject2(){
        try{
            $store = $this->getDocumentStore();
            $options = (new SessionOptions())->setDatabase($store->getDatabase());
            try {
                $session = $store->openSession($options);
                $family = (new Family())->setNames("Hibernating Rhinos","RavenDB");
                $session->store($family,"family/1");
                $session->saveChanges();

                $newFamily = $session->load(Family::class,"family/1");
                $newFamily->setNames(["Hibernating Rhinos", "RavenDB"]);
                // ASSERT #1
                $newFamily->setNames(["RavenDB", "Hibernating Rhinos"]);
                // ASSERT #2

                $session->saveChanges();
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
    public function testCrudOperationsWithArrayInObject3(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            try {
                $session = $store->openSession($options);
                $family = (new Family())->setNames(["Hibernating Rhinos","RavenDB"]);
               // $session->store($family,"family/1");
                $newFamily = $session->load(Family::class,"family/1");
             //   $newFamily->setNames(["RavenDB"]);
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
    public function testCrudOperationsWithArrayInObject4(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            try {
                $session = $store->openSession($options);
                $family = (new Family())->setNames(["Hibernating Rhinos", "RavenDB"]);
                $session->store($family,"family/1");
                $session->saveChanges();

                $newFamily = $session->load(Family::class,"family/1");
                $newFamily->setNames(["RavenDB", "Hibernating Rhinos", "Toli", "Mitzi", "Boki"]);
                // whatChanged ASSERTION
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
    public function testCrudOperationsWithWhatChanged(){
        try{
            $store = $this->getDocumentStore();
            $options = (new SessionOptions())->setDatabase($store->getDatabase());
            try {
                $session = $store->openSession($options);

                $user1 = (new User())->setLastName("user1");
                $session->store($user1,"users/1");

                $user2 = (new User())->setName("user2")->setAge(1);
                $session->store($user2,"users/2");

                $user3 = new User();
                $user3->setName("user3")->setAge(1);
                $session->store($user3,"users/3");

                $user4 = (new User())->setName("user4");
                $session->store($user4,"users/4");

                $session->delete($user2);
                $user3->setAge(3);
                // ASSERTION #1
                $tempUser = $session->load(User::class,"users/2");
                AssertUtils::assertThat($tempUser->getAge())::isNull();
                // ASSERTION #2
                $tempUser = $session->load(User::class,"users/3");
                AssertUtils::assertThat($tempUser->getAge())::isEqualTo(3);
                // ASSERTION #3
                $user1 = $session->load(User::class,"users/1");
                $user4 = $session->load(User::class,"users/4");
                $session->delete($user4);
                $user1->setAge(10);
                // whatChanged ASSERTION :: TO RUN BEFORE SAVE CHANGE
                $session->saveChanges();

                $tempUser = $session->load(User::class,"users/4");
                AssertUtils::assertThat($tempUser)::isNull();

                $tempUser = $session->load(User::class,"users/1");
                AssertUtils::assertThat($tempUser)::isEqualTo(10);

            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
    public function testCrudOperations(){
        try{
            $store = $this->getDocumentStore();
            $options = (new SessionOptions());
            $options->setDatabase($store->getDatabase());
            try {
                $session = $store->openSession($options);
              //  $user1 = (new User())->setLastName("user1");
              //  $session->store($user1,"users/1",'adfkasdjfd');

                $user2 = (new User())->setName("user2");
                $session->store($user2,"users/2",'Vectorfdfdfd');

                $user3 = new User();
                $user3->setName("user3")->setAge(1);
                $session->store($user3,"users/3",'Vectorfdfdfd');

                $user4 = (new User())->setName("user4");
                $session->store($user4,"users/4",'Vectorfdfdfd');

                $session->delete($user2);
                $user3->setAge(3);
                $session->saveChanges();

                $tempUser = $session->load(User::class,"users/2");
                AssertUtils::assertThat($tempUser)::isNull();

                $tempUser = $session->load(User::class,"users/3");
                AssertUtils::assertThat($tempUser->getAge())::isEqualTo(3);

                $user1 = $session->load(User::class,"users/1");
                $user4 = $session->load(User::class,"users/4");

                $session->delete($user4);
                $user1->setAge(10);
                $session->saveChanges();

                $tempUser = $session->load(User::class,"users/4");
                AssertUtils::assertThat($tempUser)::isNull();

                $tempUser = $session->load(User::class,"users/1");
                AssertUtils::assertThat($tempUser->getAge())::isEqualTo(10);

            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
}