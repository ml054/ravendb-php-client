<?php
namespace RavenDB\Client\Serverwide\Commands;

use RavenDB\Tests\Client\RemoteTestBase;

class GetTopologyTest extends RemoteTestBase
{
    public function testCanGetTopology(){
        try{
            $store = $this->getDocumentStore();
            try {
                $command = new GetDatabaseTopologyCommand(null,null);
                $store->getRequestExecutor($store->getDatabase())->execute($command);
            } finally {
                $store->close();
            }
        } finally {
            $store->close();
        }
    }
}
