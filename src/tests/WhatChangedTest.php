<?php

namespace RavenDB\Tests;

use RavenDB\Client\Documents\Session\SessionOptions;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\CrudEntities\FamilyMembers;
use RavenDB\Tests\Client\RemoteTestBase;

class WhatChangedTest extends RemoteTestBase
{
    public function testCanDetectChanges(){
        try{
            $store = $this->getDocumentStore();
            $options = new SessionOptions();
            $options->setDatabase($store->getDatabase());
            $options->setRequestExecutor($store->getRequestExecutor());
            try {
                $session = $store->openSession($options);
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
