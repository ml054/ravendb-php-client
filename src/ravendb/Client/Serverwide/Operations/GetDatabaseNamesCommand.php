<?php

namespace RavenDB\Client\Serverwide\Operations;

use Exception;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Http\HttpClient;

class GetDatabaseNamesCommand extends RavenCommand
{
    private int $_start;
    private int $_pageSize;

    public function __construct(int $_start, int $_pageSize)
    {
        $this->_start = $_start;
        $this->_pageSize = $_pageSize;
    }

    public function isReadRequest(): bool
    {
        return true;
    }

    public function createRequest(ServerNode $node, &$url): string
    {
        $requestURL = $node->getUrl() . "/databases?start=" . $this->_start . "&pageSize=" . $this->_pageSize . "&namesOnly=true";
        return (new HttpClient())->curlRequest($requestURL);
    }

    public static function invalidResponseException(?Exception $cause)
    {
        try {
            parent::throwInvalidResponse($cause);
        } catch (IllegalStateException $e) {
        }
    }

    /**
     * @param string $response
     * @param bool $fromCache
     * @return array
     */
    public function setResponse(string $response, bool $fromCache): void
    {
        if (null === $response) {
            self::invalidResponseException(null);
        }

        $names = json_decode($response);

        if (!property_exists($names, "Databases")) {
            self::invalidResponseException(null);
        }

        $databases = $names->Databases;
        if (!is_array($databases)) {
            self::invalidResponseException(null);
        }
        $this->result = $databases;
    }
}