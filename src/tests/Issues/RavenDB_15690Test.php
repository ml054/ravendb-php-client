<?php

namespace RavenDB\Tests\Issues;

use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\CrudEntities\Company;
use RavenDB\Tests\Client\RemoteTestBase;

class RavenDB_15690Test extends RemoteTestBase
{
    /**
     * @throws \Exception
     */
    public function testHasChanges_ShouldDetectDeletes(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            try {
                $session = $store->openSession($options);
                $company = new Company();
                $company->setName("HR");
                $session->store(Company::class,"companies/1");
                $session->saveChanges();
            } finally {
                $store->close();
            }

            try {
                $company = $session->load(Company::class,"companies/1");
                $session->delete($company);
                $changes = $session->advanced()->whatChanged();
                AssertUtils::assertThat($changes)::hasSize(1);
                AssertUtils::assertThat($session->advanced()->hasChanges())::isTrue();

            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
}
