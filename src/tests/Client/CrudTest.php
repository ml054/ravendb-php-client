<?php /** @noinspection ALL */

namespace RavenDB\Tests\Client;

use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Infrastructure\Entities\User;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\CrudEntities\Member;

class CrudTest extends RemoteTestBase
{
    public function testCrudCanUpdatePropertyToNull(){
            try{

                $store = $this->getDocumentStore();
                $options = new SessionOptions();
                $options->setDatabase($store->getDatabase());
                $options->setRequestExecutor($store->getRequestExecutor());

                try {
                    $session = $store->openSession($options);
                    $user = new User();
                    $user->setName("John");
                    $session->store($user,"members/1");

                    $user2 = new User();
                    $user2->setName("Do");
                    $session->store($user2,"members/2");

                    $session->saveChanges();

                } finally {
                    $store->close();
                }

                try {

                    $session = $store->openSession($options);
                    $user = $session->load(User::class,"009dce03-73ac-4afb-97dd-1df0e0fc3086");
                    $user->setName(null);
                    $session->saveChanges();
                    AssertUtils::assertThat($user)::isInstanceOf(User::class); // <--- expected to fail

                } finally {
                    $store->close();
                }

            } finally {
                $store->close();
            }
    }
}
