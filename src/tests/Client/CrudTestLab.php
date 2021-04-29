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
                $poc->setName("New Test Name");
                $session->store($poc, "users/78");
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
                     */
                    $user = $session->load(User::class,"8cdc509e-1659-4731-81ef-58c44ac96744");
                    $user->setName("Nemo Test");
                    $session->saveChanges();
                    AssertUtils::assertThat($user)::isInstanceOf(User::class);
                } finally {
                    $store->close();
                }
            } finally {
                $store->close();
            }
        }
}