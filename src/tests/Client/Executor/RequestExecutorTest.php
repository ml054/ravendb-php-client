<?php

namespace RavenDB\Tests\Client\Executor;

use Exception;
use RavenDB\Client\Documents\Commands\GetNextOperationIdCommand;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Serverwide\Operations\GetDatabaseNamesOperation;
use RavenDB\Tests\Client\RemoteTestBase;
class RequestExecutorTest extends RemoteTestBase
{
    // TODO: TEST RUN PENDING ON THE DEPENDENCIES MIGRATION
    public function testCanFetchDatabasesNames()
    {
        $conventions = new DocumentConventions();
        $store = $this->getDocumentStore('db-1',false,null);
        try {
            $executor = RequestExecutor::create($store->getUrls(), $store->getDatabase(), null, $conventions);
          //  dd($executor);
            try {
                $databaseNamesOperation = new GetDatabaseNamesOperation(0, 20);
                $command = $databaseNamesOperation->getCommand($conventions);
                $executor->execute($command);
              //  dd($command->getResult());
                $dbNames = $command->getResult();
                // TODO: IMPLEMENT assertAs UTIL FOLLOWING API CONVENTION
                $isStoreDbName = in_array($store->getDatabase(), $dbNames);
                $this->assertTrue($isStoreDbName);
            } finally {
                $executor->close();
            }
        } finally {
            $store->close();
        }
    }
}

/*
 * package net.ravendb.client.executor;
public class RequestExecutorTest extends RemoteTestBase {

    @Test
    public void failuresDoesNotBlockConnectionPool() throws Exception {
        DocumentConventions conventions = new DocumentConventions();

        try (DocumentStore store = getDocumentStore()) {
            try (RequestExecutor executor = RequestExecutor.create(store.getUrls(), "no_such_db", null, null,null, store.getExecutorService(), conventions)) {
                int errorsCount = 0;

                for (int i = 0; i < 40; i++) {
                    try {
                        GetNextOperationIdCommand command = new GetNextOperationIdCommand();
                        executor.execute(command);
                    } catch (Exception e) {
                        errorsCount++;
                    }
                }

                assertThat(errorsCount).isEqualTo(40);

                assertThatThrownBy(() -> {
                    GetDatabaseNamesOperation databaseNamesOperation = new GetDatabaseNamesOperation(0, 20);
                    RavenCommand<String[]> command = databaseNamesOperation.getCommand(conventions);
                    executor.execute(command);
                }).isExactlyInstanceOf(DatabaseDoesNotExistException.class);
            }
        }
    }


    @Test
    public void canIssueManyRequests() throws Exception {
        DocumentConventions conventions = new DocumentConventions();

        try (DocumentStore store = getDocumentStore()) {
            try (RequestExecutor executor = RequestExecutor.create(store.getUrls(), store.getDatabase(), null, null, null, store.getExecutorService(), conventions)) {
                for (int i = 0; i < 50; i++) {
                    GetDatabaseNamesOperation databaseNamesOperation = new GetDatabaseNamesOperation(0, 20);
                    RavenCommand<String[]> command = databaseNamesOperation.getCommand(conventions);
                    executor.execute(command);
                }
            }
        }
    }

    @Test
    public void throwsWhenUpdatingTopologyOfNotExistingDb() throws Exception {
        DocumentConventions conventions = new DocumentConventions();

        try (DocumentStore store = getDocumentStore()) {
            try (RequestExecutor executor = RequestExecutor.create(store.getUrls(), "no_such_db", null, null,null, store.getExecutorService(), conventions)) {

                ServerNode serverNode = new ServerNode();
                serverNode.setUrl(store.getUrls()[0]);
                serverNode.setDatabase("no_such");

                UpdateTopologyParameters updateTopologyParameters = new UpdateTopologyParameters(serverNode);
                updateTopologyParameters.setTimeoutInMs(5000);

                assertThatThrownBy(() ->
                        ExceptionsUtils.accept(() ->
                                executor.updateTopologyAsync(updateTopologyParameters).get()))
                        .isExactlyInstanceOf(DatabaseDoesNotExistException.class);
            }
        }
    }

    @Test
    public void throwsWhenDatabaseDoesNotExist() throws Exception {
        DocumentConventions conventions = new DocumentConventions();

        try (DocumentStore store = getDocumentStore()) {
            try (RequestExecutor executor = RequestExecutor.create(store.getUrls(), "no_such_db", null, null,null, store.getExecutorService(), conventions)) {

                GetNextOperationIdCommand command = new GetNextOperationIdCommand();

                assertThatThrownBy(() ->
                        executor.execute(command))
                        .isExactlyInstanceOf(DatabaseDoesNotExistException.class);
            }
        }
    }

    @Test
    public void canCreateSingleNodeRequestExecutor() throws Exception {
        DocumentConventions documentConventions = new DocumentConventions();
        try (DocumentStore store = getDocumentStore()) {
            try (RequestExecutor executor = RequestExecutor.createForSingleNodeWithoutConfigurationUpdates(store.getUrls()[0], store.getDatabase(), null, null,null, store.getExecutorService(), documentConventions)) {

                List<ServerNode> nodes = executor.getTopologyNodes();

                assertThat(nodes.size()).isEqualTo(1);

                ServerNode serverNode = nodes.get(0);
                assertThat(serverNode.getUrl()).isEqualTo(store.getUrls()[0]);
                assertThat(serverNode.getDatabase()).isEqualTo(store.getDatabase());

                GetNextOperationIdCommand command = new GetNextOperationIdCommand();

                executor.execute(command);

                assertThat(command.getResult()).isNotNull();
            }
        }
    }

    @Test
    public void canChooseOnlineNode() throws Exception {
        DocumentConventions documentConventions = new DocumentConventions();

        try (DocumentStore store = getDocumentStore()) {
            String url = store.getUrls()[0];
            String dbName = store.getDatabase();

            try (RequestExecutor executor = RequestExecutor.create(new String[]{"http://no_such_host:8080", "http://another_offlilne:8080", url}, dbName, null,null, null, store.getExecutorService(), documentConventions)) {
                GetNextOperationIdCommand command = new GetNextOperationIdCommand();
                executor.execute(command);

                assertThat(command.getResult()).isNotNull();

                List<ServerNode> topologyNodes = executor.getTopologyNodes();

                assertThat(topologyNodes)
                        .hasSize(1);

                assertThat(topologyNodes.get(0).getUrl().equals(url))
                        .isTrue();

                assertThat(executor.getUrl()).isEqualTo(url);
            }
        }
    }

    @Test
    public void failsWhenServerIsOffline() throws Exception {
        DocumentConventions documentConventions = new DocumentConventions();

        try (DocumentStore store = getDocumentStore()) {

            assertThatThrownBy(() -> {
                // don't even start server
                try (RequestExecutor executor = RequestExecutor.create(new String[]{"http://no_such_host:8081"}, "db1", null, null,null, store.getExecutorService(), documentConventions)) {
                    GetNextOperationIdCommand command = new GetNextOperationIdCommand();

                    executor.execute(command);
                }
            }).hasCauseInstanceOf(UnknownHostException.class);
        }
    }
}

*/