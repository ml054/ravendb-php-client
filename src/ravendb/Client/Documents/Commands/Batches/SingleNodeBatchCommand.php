<?php

namespace RavenDB\Client\Documents\Commands\Batches;

use RavenDB\Client\Data\Driver\RavenDB;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Methods\HttpRequestBase;
use RavenDB\Client\Primitives\Closable;

class SingleNodeBatchCommand extends RavenCommand implements Closable
{
    private DocumentConventions $_conventions;
    private ?array $_commands=null;
    private string $_mode;
    private array $_attachmentStreams;
    private ?BatchOptions $_options=null;
    private const TRANSACTION_MODE_SINGLE_NODE = "SINGLE_NODE"; // NO ENUM YET IN PHP
    private const TRANSACTION_MODE_CLUSTER_WIDE = "CLUSTER_WIDE"; // NO ENUM YET IN PHP

    public function __construct(DocumentConventions $conventions, ?array $commands, ?BatchOptions $options)
    {
        parent::__construct(BatchCommandResult::class);
        $this->_commands = $commands;
        $this->_options = $options;
        $this->_conventions = $conventions;
        $this->_mode = self::TRANSACTION_MODE_SINGLE_NODE;

        if(null === $conventions){
            throw new \InvalidArgumentException("conventions cannot be null");
        }
        if(null === $commands){
            throw new \InvalidArgumentException("commands cannot be null");
        }
    }

    /**
     * @throws \Exception
     */
    public function createRequest(ServerNode $node): array|string|object
    {
        $url = $node->getUrl()."/databases/".$node->getDatabase()."/bulk_docs";
        $httpClient = new HttpRequestBase();
        $body = '[{"name":"Hibernating Rhinos","age":8,"id":"person\/5"},{"name":"RavenDB","age":4,"id":"person\/6"}]';
        $curlopt = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST=>"2",
            CURLOPT_SSL_VERIFYPEER=>"1",
            CURLOPT_CUSTOMREQUEST=>"POST",
            CURLOPT_POSTFIELDS=>$body,
            CURLOPT_HEADEROPT=>[
                "application/json"
            ]
        ];
        return $httpClient->createCurlRequest($url,$curlopt);
    }

    public function close()
    {
        // TODO: Implement close() method.
    }

    public function isReadRequest(): bool
    {
        // TODO: Implement isReadRequest() method.
    }
}
