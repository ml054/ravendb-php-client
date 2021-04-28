<?php

namespace RavenDB\Client\Documents\Commands\Batches;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Constants;
use RavenDB\Client\Data\Driver\RavenDB;
use RavenDB\Client\Documents\Batches\Command;
use RavenDB\Client\Documents\Batches\Operations\Document;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Extensions\JsonExtensions;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Methods\HttpRequestBase;
use RavenDB\Client\Primitives\Closable;

class SingleNodeBatchCommand extends RavenCommand implements Closable
{
    private DocumentConventions $_conventions;
    private ArrayCollection $_commands;
    private string $_mode;
    private array $_attachmentStreams;
    private ?BatchOptions $_options=null;
    private const TRANSACTION_MODE_SINGLE_NODE = "SINGLE_NODE"; // NO ENUM YET IN PHP
    private const TRANSACTION_MODE_CLUSTER_WIDE = "CLUSTER_WIDE"; // NO ENUM YET IN PHP
    private object $internalSerializer;
    /**
     * @throws \Exception
     */
    public function __construct(DocumentConventions $conventions, ArrayCollection $commands, ?BatchOptions $options)
    {
        parent::__construct(BatchCommandResult::class);

        $this->_commands = $commands;
        $this->_options = $options;
        // TODO IMPLEMENT CONVENTIONS DURING THE SAVING PROCESS. TO CHECK WITH TECH. RESOURCE AVAILABLE BUT ALL SET TO NULL
        $this->_conventions = $conventions;
        $this->_mode = self::TRANSACTION_MODE_SINGLE_NODE;
        $this->internalSerializer = JsonExtensions::storeSerializer();
        if(null === $conventions){
            throw new \InvalidArgumentException("conventions cannot be null");
        }
        if(null === $commands){
            throw new \InvalidArgumentException("commands cannot be null");
        }
        $commandsCollection = $this->_commands->getValues();

        // JUST FOR THE PURPOSE OF CONFIRMING THE INSTANCE OF THE COMMANDS FOR NOW
        foreach($commandsCollection as $command){
            if(!$command instanceof PutCommandDataWithJson) throw new \Exception("Wrong command submitted.");
        }
    }

    /**
     * @throws \Exception
     * TODO format the response
     */
    public function createRequest(ServerNode $node): array|string|object
    {
        $commands = $this->_commands->getValues();

        $documents = [];
        foreach($commands as $index=>$command){
            // THE COMMAND IS AN ARRAYCOLLECTION GIVING ACCESS TO OBJECT LIKE TARGET
            $type = $command->getType();
            $documents[] = (new Document($type))->setDocument($command->getDocument()->getValue());
        }

        $command = (new Command())->setCommands($documents);
        $request = $this->internalSerializer->serialize($command,'json');

        $httpClient = new HttpRequestBase();
        $url = $node->getUrl()."/databases/".$node->getDatabase()."/bulk_docs";

        $curlopt = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER =>Constants::CURLOPT_RETURNTRANSFER,
            CURLOPT_SSL_VERIFYHOST=>Constants::CURLOPT_SSL_VERIFYHOST,
            CURLOPT_SSL_VERIFYPEER=>Constants::CURLOPT_SSL_VERIFYPEER,
            CURLOPT_POSTFIELDS=>$request,
            CURLOPT_HTTPHEADER=>[
               Constants::HEADERS_CONTENT_TYPE_APPLICATION_JSON
            ]
        ];
        return $httpClient->createCurlRequest($url,$curlopt);
    }

    public function setResponse(array|string $response, bool $fromCache)
    {
        if (null === $response) {
            self::throwInvalidResponse(null);
        }

        dd($response,__METHOD__);
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
