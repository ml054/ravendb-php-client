<?php

namespace RavenDB\Tests\Client\Driver;

use Exception;
use RavenDB\Tests\Client\Util\StringUtils;
use RavenDB\Tests\Client\Util\System;

class RavenServerLocator
{
    public static string $ENV_SERVER_PATH = "RAVENDB_JAVA_TEST_SERVER_PATH";

    /**
     * @throws Exception
    */
    public function getServerPath(): string
    {
        $path = System::getenv(self::$ENV_SERVER_PATH);
        if (StringUtils::isBlank($path)) {
            throw new Exception("Unable to find RavenDB server path. " .
                "Please make sure " . self::$ENV_SERVER_PATH . " environment variable is set and is valid " .
                "(current value = " . $path . ")");
        }
        return $path;
    }

    public function getCommand(): string
    {
        return $this->getServerPath();
    }

    public function getCommandArguments(): array
    {
        return [];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getServerCertificatePath(): string
    {
        throw new Exception(null);
    }
    /**
     * @return string
     * @throws Exception
    */
    public function getServerCaPath(): string
    {
        throw new Exception(null);
    }

}
