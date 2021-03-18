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
            $command = new GetClusterTopologyCommand(null );
            $store->getRequestExecutor()->execute($command);
            /**
             * @var ClusterTopologyResponse $result
            */
            $result = $command->getResult();
            AssertUtils::assertThat($result)::isNotNull();
            AssertUtils::assertThat($result->getLeader())::isNotEmpty(); // TODO : MAPPER SHOULD TRIGGER THE GETTER
            AssertUtils::assertThat($result->getNodeTag())::isNotEmpty();
            $topology = $result->getTopology();
            AssertUtils::assertThat($topology)::isNotNull();
            AssertUtils::assertThat($topology->getTopologyId())::isNotNull();
            AssertUtils::assertThat($topology->getMembers())::hasSize(1); // TODO CHECK WITH MARCIN, OBJECT CONVERTED TO ARRAY FOR ITERATION
            AssertUtils::assertThat($topology->getWatchers())::hasSize(0);
            AssertUtils::assertThat($topology->getPromotables())::hasSize(0);
        } finally {
            $store->close();
        }
    }
}
/* TODO: Note : no documentConventions, only one try catch triggered
 * TODO ASSERT USING THE OUTPUT KEYS EXPECTED
 *
 * TODO: TASK RECEIVED TO CHECK COMMAND TO SERVER: source REQUESTEXECUTOR
 *
 *     public <TResult> void execute(ServerNode chosenNode, Integer nodeIndex, RavenCommand<TResult> command, boolean shouldRetry, SessionInfo sessionInfo, Reference<HttpRequestBase> requestRef) {
        if (command.failoverTopologyEtag == INITIAL_TOPOLOGY_ETAG) {
            command.failoverTopologyEtag = INITIAL_TOPOLOGY_ETAG;
            if (_nodeSelector != null && _nodeSelector.getTopology() != null) {
                Topology topology = _nodeSelector.getTopology();
                if (topology.getEtag() != null) {
                    command.failoverTopologyEtag = topology.getEtag();
                }
            }
        }

        Reference<String> urlRef = new Reference<>();
        HttpRequestBase request = createRequest(chosenNode, command, urlRef);

        if (request == null) {
            return;
        }

        if (requestRef != null) {
            requestRef.value = request;
        }

        //noinspection SimplifiableConditionalExpression
        boolean noCaching = sessionInfo != null ? sessionInfo.isNoCaching() : false;

        Reference<String> cachedChangeVectorRef = new Reference<>();
        Reference<String> cachedValue = new Reference<>();

        try (HttpCache.ReleaseCacheItem cachedItem = getFromCache(command, !noCaching, urlRef.value, cachedChangeVectorRef, cachedValue)) {
            if (cachedChangeVectorRef.value != null) {
                if (tryGetFromCache(command, cachedItem, cachedValue.value)) {
                    return;
                }
            }

            setRequestHeaders(sessionInfo, cachedChangeVectorRef.value, request);

            command.numberOfAttempts = command.numberOfAttempts + 1;
            int attemptNum = command.numberOfAttempts;
            EventHelper.invoke(_onBeforeRequest, this, new BeforeRequestEventArgs(_databaseName, urlRef.value, request, attemptNum));

            CloseableHttpResponse response = sendRequestToServer(chosenNode, nodeIndex, command, shouldRetry, sessionInfo, request, urlRef.value);

            if (response == null) {
                return;
            }

            CompletableFuture<Void> refreshTask = refreshIfNeeded(chosenNode, response);

            command.statusCode = response.getStatusLine().getStatusCode();

            ResponseDisposeHandling responseDispose = ResponseDisposeHandling.AUTOMATIC;

            try {
                if (response.getStatusLine().getStatusCode() == HttpStatus.SC_NOT_MODIFIED) {
                    EventHelper.invoke(_onSucceedRequest, this, new SucceedRequestEventArgs(_databaseName, urlRef.value, response, request, attemptNum));

                    cachedItem.notModified();

                    try {
                        if (command.getResponseType() == RavenCommandResponseType.OBJECT) {
                            command.setResponse(cachedValue.value, true);
                        }
                    } catch (IOException e) {
                        throw ExceptionsUtils.unwrapException(e);
                    }

                    return;
                }

                if (response.getStatusLine().getStatusCode() >= 400) {
                    if (!handleUnsuccessfulResponse(chosenNode, nodeIndex, command, request, response, urlRef.value, sessionInfo, shouldRetry)) {
                        Header dbMissingHeader = response.getFirstHeader("Database-Missing");
                        if (dbMissingHeader != null && dbMissingHeader.getValue() != null) {
                            throw new DatabaseDoesNotExistException(dbMissingHeader.getValue());
                        }

                        throwFailedToContactAllNodes(command, request);
                    }
                    return; // we either handled this already in the unsuccessful response or we are throwing
                }

                EventHelper.invoke(_onSucceedRequest, this, new SucceedRequestEventArgs(_databaseName, urlRef.value, response, request, attemptNum));

                responseDispose = command.processResponse(cache, response, urlRef.value);
                _lastReturnedResponse = new Date();
            } finally {
                if (responseDispose == ResponseDisposeHandling.AUTOMATIC) {
                    IOUtils.closeQuietly(response, null);
                }

                try {
                    refreshTask.get();
                } catch (Exception e) {
                    //noinspection ThrowFromFinallyBlock
                    throw ExceptionsUtils.unwrapException(e);
                }
            }
        }
    }

 *
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