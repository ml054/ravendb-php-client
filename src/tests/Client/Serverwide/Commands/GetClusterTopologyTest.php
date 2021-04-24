<?php

namespace RavenDB\Tests\Client\Serverwide\Commands;
use Doctrine\Common\Annotations\AnnotationReader;
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
    /**
     * @throws \Exception
     */
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
        } catch (ExceptionInterface $e) {
            dd($e->getMessage());
        }
        $result = $serializer->encode(StringUtils::pascalize($normalize),'json');
        AssertUtils::assertThat($result)::isNotNull();
        AssertUtils::assertThat($result)::isString();
        AssertUtils::assertThat($result)::isJson();
    }
}
