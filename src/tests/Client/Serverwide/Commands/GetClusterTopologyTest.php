<?php

namespace RavenDB\Tests\Client\Serverwide\Commands;
use RavenDB\Client\Http\ClusterTopologyResponse;
use RavenDB\Client\Serverwide\Commands\GetClusterTopologyCommand;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\RemoteTestBase;

class GetClusterTopologyTest extends RemoteTestBase
{
    public function testCanGetTopology()
    {
        $store = $this->getDocumentStore();
        try {
            $command = new GetClusterTopologyCommand();
            $store->getRequestExecutor()->execute($command);
            /**
             * @var ClusterTopologyResponse $result
            */
            $result = $command->getResult();

            AssertUtils::assertThat($result)::isNotNull();
            AssertUtils::assertThat($result->getLeader())::isNotEmpty();
            AssertUtils::assertThat($result->getNodeTag())::isNotEmpty();

            $topology = $result->getTopology();
            AssertUtils::assertThat($topology)::isNotNull();
            AssertUtils::assertThat($topology->getTopologyId())::isNotNull();
            AssertUtils::assertThat($topology->getMembers())::hasSize(1);
            AssertUtils::assertThat($topology->getWatchers())::hasSize(0);
            AssertUtils::assertThat($topology->getPromotables())::hasSize(0);

        } finally {
            $store->close();
        }
    }
}
