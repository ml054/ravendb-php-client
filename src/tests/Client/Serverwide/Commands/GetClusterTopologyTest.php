<?php


namespace RavenDB\Tests\Client\Serverwide\Commands;


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
            $result = json_decode($command->getResult(), true);
            // TODO : COMPLIANCE TO IMPLEMENT ::: ClusterTopologyResponse result = command.getResult();
            //
            AssertUtils::assertThat($result)::isNotNull();
            AssertUtils::assertThat($result["Leader"])::isNotEmpty();
            AssertUtils::assertThat($result["NodeTag"])::isNotEmpty();
            $topology = $result["Topology"];
            AssertUtils::assertThat($topology)::isNotNull();
            AssertUtils::assertThat($topology["TopologyId"])::isNotNull();
            AssertUtils::assertThat($topology["Members"])::hasSize(1);
            AssertUtils::assertThat($topology["Watchers"])::hasSize(0);
            AssertUtils::assertThat($topology["Promotables"])::hasSize(0);
        } finally {
            $store->close();
        }
    }
}
/* TODO: Note : no documentConventions, only one try catch triggered
 * TODO ASSERT USING THE OUTPUT KEYS EXPECTED
 * {#28
  +"Topology": {#14
    +"TopologyId": "405a214a-a5d3-4e2f-b404-68e3a0aa1623"
    +"AllNodes": {#7
      +"A": "http://devtool.infra:9095"
    }
    +"Members": {#337
      +"A": "http://devtool.infra:9095"
    }
    +"Promotables": {#10}
    +"Watchers": {#22}
    +"LastNodeId": "A"
    +"Etag": 1
  }
  +"Etag": 1
  +"Leader": "A"
  +"LeaderShipDuration": 1781137359
  +"CurrentState": "Leader"
  +"NodeTag": "A"
  +"CurrentTerm": 1
  +"NodeLicenseDetails": {#27
    +"A": {#21
      +"UtilizedCores": 2
      +"MaxUtilizedCores": null
      +"NumberOfCores": 2
      +"InstalledMemoryInGb": 1.2521171569824
      +"UsableMemoryInGb": 1.2521171569824
      +"BuildInfo": {#25
        +"ProductVersion": "5.1"
        +"BuildVersion": 51016
        +"CommitHash": "f8d499a"
        +"FullVersion": "5.1.4"
      }
      +"OsInfo": {#26
        +"Type": "Linux"
        +"FullName": "CentOS Linux 8"
        +"Version": "8.3.2011"
        +"BuildVersion": "4.18.0-240.el8.x86_64"
        +"Is64Bit": true
      }
    }
  }
  +"LastStateChangeReason": "Leader, I'm the only one in the cluster, so I'm the leader (at 02/18/2021 21:06:31)"
  +"Status": {#32}
}
// TODO: JUST FOR REVISION
 public void canGetTopology() throws Exception {
        try (IDocumentStore store = getDocumentStore()) {

            GetClusterTopologyCommand command = new GetClusterTopologyCommand();

            store.getRequestExecutor().execute(command);

            ClusterTopologyResponse result = command.getResult();

            assertThat(result)
                    .isNotNull();

            assertThat(result.getLeader())
                    .isNotEmpty();

            assertThat(result.getNodeTag())
                    .isNotEmpty();

            ClusterTopology topology = result.getTopology();

            assertThat(topology)
                    .isNotNull();

            assertThat(topology.getTopologyId())
                    .isNotNull();

            assertThat(topology.getMembers())
                    .hasSize(1);

            assertThat(topology.getWatchers())
                    .hasSize(0);

            assertThat(topology.getPromotables())
                    .hasSize(0);
        }
    }
 * */