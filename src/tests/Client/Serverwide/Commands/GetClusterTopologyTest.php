<?php


namespace RavenDB\Tests\Client\Serverwide\Commands;


use RavenDB\Tests\Client\RemoteTestBase;

class GetClusterTopologyTest extends RemoteTestBase
{
    /* TODO:
     *
public class GetClusterTopologyTest extends RemoteTestBase {
    @Test
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
}
     * */
}