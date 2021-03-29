<?php

namespace RavenDB\Tests\Client\DocumentStore;

use Exception;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Tests\Client\RemoteTestBase;

class TestValidateUrls extends RemoteTestBase
{
        public function testValidateUrls(){
            $initialUrls = ["http://www.example.com/","http://devtool.dev"];
            try {
                $validation = RequestExecutor::validateUrls($initialUrls);
            } catch (Exception $e) {
            }
            dd($validation);
        }
}
