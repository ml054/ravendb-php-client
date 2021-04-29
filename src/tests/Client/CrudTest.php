<?php /** @noinspection ALL */

namespace RavenDB\Tests\Client;

use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Infrastructure\Entities\User;

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
                   $user->setName("Raven DB NoSql");

                   $session->store($user,"users/1");
                   $session->saveChanges();

                } finally {
                    $store->close();
                }

                try {
                    /*$session = $store->openSession($options);
                    $user = $session->load(User::class,"users/1");
                    $user->setName(null);
                    $session->saveChanges();*/
                } finally {
                    $store->close();
                }

            } finally {
                $store->close();
            }
        }
}
