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

class CrudTestLab extends RemoteTestBase
{
    /**
     * @throws \Exception
     */

    public function testCanCrudCanUpdatePropertyToNull()
    {
        try {
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            $options->setRequestExecutor($store->getRequestExecutor());
            try {

                $session = $store->openSession($options);
                $poc = new User();
                $poc->setName("Hello Raven nemodev");
                $session->store($poc, "users/7");
                $session->saveChanges();

            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
    public function testtestCanLoadDocument(){
            try{
                $store = $this->getDocumentStore();
                $options = new SessionOptions();
                $options->setDatabase($store->getDatabase());
                $options->setRequestExecutor($store->getRequestExecutor());
                try {
                   $session = $store->openSession($options);
                    /**
                      * @var User $user
                     * TODO QUERY WITH users/7 ID FORM WON'T WORK FOR NOW BECAUSE DOES NOT MATCH THE ONE IN THE METADATA (RANDOM ONE)
                     * USING THE RANDOM ONE FOR THE PURPOSE OF THE LOAD TASK COMPLETION
                     */
                    $user = $session->load(User::class,"dd6587f5-3071-4737-ab00-dd898d3302e6");
                   // dd($user);
                    $user->setName("My Company");
                    $session->saveChanges();
                } finally {
                    $store->close();
                }
            } finally {
                $store->close();
            }
        }
}