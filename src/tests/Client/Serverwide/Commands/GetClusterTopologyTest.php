<?php

namespace RavenDB\Tests\Client\Serverwide\Commands;
use Doctrine\Common\Annotations\AnnotationReader;
use Nahid\JsonQ\Jsonq;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Http\ClusterTopologyResponse;
use RavenDB\Client\Serverwide\Commands\GetClusterTopologyCommand;
use RavenDB\Client\Serverwide\DatabaseRecord;
use RavenDB\Client\Util\AssertUtils;
use RavenDB\Client\Util\StringUtils;
use RavenDB\Tests\Client\RemoteTestBase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    public function testCanClusterTopologyResponse(){
        $json = <<<EOD
{
    "Topology": {
        "TopologyId": "405a214a-a5d3-4e2f-b404-68e3a0aa1623",
        "AllNodes": {
            "A": "http://devtool.infra:9095"
        },
        "Members": {
            "A": "http://devtool.infra:9095"
        },
        "Promotables": {},
        "Watchers": {},
        "LastNodeId": "A",
        "Etag": 75
    },
    "Etag": 75,
    "Leader": "A",
    "LeaderShipDuration": 332107975,
    "CurrentState": "Leader",
    "NodeTag": "A",
    "CurrentTerm": 2,
    "NodeLicenseDetails": {
        "A": {
            "UtilizedCores": 2,
            "MaxUtilizedCores": null,
            "NumberOfCores": 2,
            "InstalledMemoryInGb": 1.2521171569824219,
            "UsableMemoryInGb": 1.2521171569824219,
            "BuildInfo": {
                "ProductVersion": "5.1",
                "BuildVersion": 51016,
                "CommitHash": "f8d499a",
                "FullVersion": "5.1.4"
            },
            "OsInfo": {
                "Type": "Linux",
                "FullName": "CentOS Linux 8",
                "Version": "8.3.2011",
                "BuildVersion": "4.18.0-240.el8.x86_64",
                "Is64Bit": true
            }
        }
    },
    "LastStateChangeReason": "Leader, I'm the only one in the cluster, so I'm the leader (at 03/27/2021 20:04:01)",
    "Status": {}
}        
EOD;
        try {
            $result = JsonExtensions::readValue($json, ClusterTopologyResponse::class);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        AssertUtils::assertThat($result)::isNotNull();
        AssertUtils::assertThat($result)::isInstanceOf(ClusterTopologyResponse::class);
    }

    /**
     * @throws \Nahid\QArray\Exceptions\InvalidNodeException
     * @throws \Nahid\QArray\Exceptions\ConditionNotAllowedException
     */
    public function testJsonQuery(){
        $json = <<<EOD
{
	"name": "products",
	"description": "Features product list",
	"vendor":{
		"name": "Computer Source BD",
		"email": "info@example.com",
		"website":"www.example.com"
	},
	"users":[
		{"id":1, "name":"Paul Martin", "location": "Paris"},
		{"id":2, "name":"Luck Edward", "location": "Lausanne"},
		{"id":3, "name":"Laurence Ann", "location": "Krakow"},
		{"id":4, "name":"Su Li", "location": "New York"},
		{"id":5, "name":"Firoz Serniabat", "location": "Berlin"},
		{"id":6, "name":"Musa Hale", "location": "Monaco", "visits": [
			{"name": "Tokyo", "year": 2016},
			{"name": "Lublin", "year": 2012},
			{"name": "Oslo", "year": 2014}
		]}
	],
	"products": [
		{"id":1, "user_id": 2, "city": "bsl", "name":"iPhone", "cat":1, "price": 80000},
		{"id":2, "user_id": 2, "city": null, "name":"macbook pro", "cat": 2, "price": 150000},
		{"id":3, "user_id": 2, "city": "dhk", "name":"Redmi 3S Prime", "cat": 1, "price": 12000},
		{"id":4, "user_id": 1, "city": null, "name":"Redmi 4X", "cat":1, "price": 15000},
		{"id":5, "user_id": 1, "city": "bsl", "name":"macbook air", "cat": 2, "price": 110000},
		{"id":6, "user_id": 2, "city": null, "name":"macbook air 1", "cat": 2, "price": 81000}
	]
}
EOD;
        $q = new Jsonq($json);
        $jsonres = $q->from('products')
            ->where('cat', '=', 2)
            ->get()->count();
        dd($jsonres);
    }


    public function testDatabaseRecordProps()
    {
        /**
         * ClassMetadataFactory && MetadataAwareNameConverter are required in order to access annotations
         */
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
        $normalizer = new GetSetMethodNormalizer($classMetadataFactory,$metadataAwareNameConverter,null);
        $serializer = new Serializer([$normalizer],[new JsonEncoder()]);

        $record = new DatabaseRecord();
        $record->setDatabaseName("db_stock");

        try {
            $normalize = $serializer->normalize($record);
           // $normalize = $serializer->serialize($record,'json');
        } catch (ExceptionInterface $e) {
            dd($e->getMessage());
        }

    }
}
