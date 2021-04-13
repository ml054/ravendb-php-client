<?php

namespace RavenDB\Tests\Client\DocumentQuery;

use RavenDB\Client\Extensions\JsonQuery;
use RavenDB\Tests\Client\RemoteTestBase;

class DocumentQueryTest extends RemoteTestBase
{
    /**
     * This is only to test the createRequest not executing anyting on the server
     * only goal being to generate the seriliazed string. Any data within this test
     * is to help reaching that goal. including static data
     * @throws \Nahid\QArray\Exceptions\InvalidNodeException
     */
    public function testQueryToString(){
        $json = <<<EOT
{
   "users":[
      {"id":1, "name":"Johura Akter Sumi", "location": "Barisal"},
      {"id":2, "name":"Mehedi Hasan Nahid", "location": "Barisal"},
      {"id":3, "name":"Ariful Islam", "location": "Barisal"},
      {"id":4, "name":"Suhel Ahmed", "location": "Sylhet"},
      {"id":5, "name":"Firoz Serniabat", "location": "Gournodi"},
      {"id":6, "name":"Musa Jewel", "location": "Barisal", "visits": [
         {"name": "Sylhet", "year": 2011},
         {"name": "Cox's Bazar", "year": 2012},
         {"name": "Bandarbar", "year": 2014}
      ]}
   ]
}
EOT;

        $jq = new JsonQuery($json);
        $res = $jq->dumpQuery();
        dd($res);

    }
}
