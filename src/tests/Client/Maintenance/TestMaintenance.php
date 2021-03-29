<?php

namespace RavenDB\Tests\Client\Maintenance;

use RavenDB\Client\Util\AssertUtils;
use RavenDB\Client\Util\ObjectUtils;
use RavenDB\Tests\Client\RemoteTestBase;

class TestMaintenance extends RemoteTestBase
{
    public function testFirstNotNull(){
        $storeDatabases = [null,null,"db1","db2"];
        $firstNotNull = ObjectUtils::firstNonNull(null,$storeDatabases);
        AssertUtils::assertThat($firstNotNull)::isString();
    }
}
