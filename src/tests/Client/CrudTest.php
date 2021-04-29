<?php /** @noinspection ALL */

namespace RavenDB\Tests\Client;

use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Infrastructure\Entities\User;
use RavenDB\Client\Util\AssertUtils;

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
                    $user->setName("First Member");
                    $session->store($user,"members/1");

                    $user2 = new User();
                    $user2->setName("First Member 2");
                    $session->store($user2,"members/2");
                    $session->saveChanges();

                } finally {
                    $store->close();
                }

                try {

                    $session = $store->openSession($options);
                    $user = $session->load(User::class,"8cdc509e-1659-4731-81ef-58c44ac96744");
                    $user->setName(null);
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
