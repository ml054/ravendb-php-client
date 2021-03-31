<?php

namespace RavenDB\Tests\Client\Executor;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Serverwide\DatabaseRecord;
use RavenDB\Client\Serverwide\Operations\CreateDatabaseOperation;
use RavenDB\Client\Serverwide\Operations\GetDatabaseNamesOperation;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Tests\Client\RemoteTestBase;

class RequestExecutorTest extends RemoteTestBase
{
    public function testCanFetchDatabasesNames()
    {
        $conventions = new DocumentConventions();
        $store = $this->getDocumentStore();
        try {
            $executor = RequestExecutor::create($store->getUrls(), $store->getDatabase(), null, $conventions);
            try {
                $databaseNamesOperation = new GetDatabaseNamesOperation(0, 20);
                $command = $databaseNamesOperation->getCommand($conventions);
                $executor->execute($command);
                $dbNames = $command->getResult();
                AssertUtils::assertThat($dbNames)::contains($store->getDatabase());
            } finally {
                $executor->close();
            }
        } finally {
            $store->close();
        }
    }

    public function testCanCreateDatabase(){
        $store = $this->getDocumentStore();
        try {
            $databaseRecord = new DatabaseRecord();
            $databaseRecord->setDatabaseName("dbnew_1");
            $operation = new CreateDatabaseOperation($databaseRecord, 0);
            $result = $store->maintenance()->server()->send($operation);
            // TODO WRITE MORE ASSERTIONS
            AssertUtils::assertThat($result)::notFoundDatabase(); // should pass the test if db not found : Test OK
        } finally {
            $store->close();
        }
    }

    public function testDatabaseRecordMapping(){
    $template = <<<EOD
{
    "DatabaseName": "",
    "Disabled": false,
    "Encrypted": false,
    "EtagForBackup": 0,
    "DeletionInProgress": {},
    "DatabaseState": "Normal",
    "Topology": {
        "Members": [
            "A"
        ],
        "Promotables": [],
        "Rehabs": [],
        "PredefinedMentors": {},
        "DemotionReasons": {},
        "PromotablesStatus": {},
        "Stamp": {
            "Index": 57,
            "Term": 1,
            "LeadersTicks": 1247190
        },
        "DynamicNodesDistribution": false,
        "ReplicationFactor": 1,
        "PriorityOrder": [],
        "DatabaseTopologyIdBase64": "Us7z4CME2E+47pmCwR3jkw"
    },
    "ConflictSolverConfig": null,
    "DocumentsCompression": {
        "Collections": [],
        "CompressRevisions": true
    },
    "Sorters": {},
    "Indexes": {},
    "IndexesHistory": {},
    "AutoIndexes": {},
    "Settings": {},
    "Revisions": null,
    "TimeSeries": null,
    "RevisionsForConflicts": null,
    "Expiration": null,
    "Refresh": null,
    "PeriodicBackups": [],
    "ExternalReplications": [],
    "SinkPullReplications": [],
    "HubPullReplications": [],
    "RavenConnectionStrings": {},
    "SqlConnectionStrings": {},
    "RavenEtls": [],
    "SqlEtls": [],
    "Client": null,
    "Studio": null,
    "TruncatedClusterTransactionCommandsCount": 0,
    "UnusedDatabaseIds": [],
    "Etag": 57
}
EOD;

    // TODO WRITE THE TEST
    }
}