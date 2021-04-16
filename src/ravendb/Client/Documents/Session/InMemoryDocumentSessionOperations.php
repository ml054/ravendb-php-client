<?php /** @noinspection ALL */

/** @noinspection PhpUnhandledExceptionInspection */

namespace RavenDB\Client\Documents\Session;

use RavenDB\Client\Documents\Commands\Batches\BatchOptions;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Documents\DocumentStoreBase;
use RavenDB\Client\Documents\IDocumentStore;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Operations\SessionOperationExecutor;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\ObjectUtils;
use RavenDB\Client\Util\StringUtils;

abstract class InMemoryDocumentSessionOperations implements Closable
{
    protected RequestExecutor $_requestExecutor;
    private OperationExecutor $_operationExecutor;
    private const TRANSACTION_MODE_SINGLE_NODE = "SINGLE_NODE"; // NO ENUM YET IN PHP
    private const TRANSACTION_MODE_CLUSTER_WIDE = "CLUSTER_WIDE"; // NO ENUM YET IN PHP
    protected SessionInfo $sessionInfo;
    protected $mapper;
    private string $id;
    private string $databaseName;
    protected DocumentStoreBase $_documentStore;
    public bool $noTracking;
    public ?BatchOptions $_saveChangesOptions=null;
    private int $numberOfRequests;
    private array $externalState;
    private array $deferredCommands;
    // TODO : PRIORITY ON THE CRUD OPERATION AND UNIT OF WORK
    protected function __construct(DocumentStoreBase $documentStore, string $id, SessionOptions $options)
    {
        $this->id = $id;
        $this->databaseName = ObjectUtils::firstNonNull([$options->getDatabase(),$documentStore->getDatabase()]);
        if(StringUtils::isBlank($this->databaseName)){
            static::throwNoDatabase();
        }
        $this->_documentStore = $documentStore;
        $this->_requestExecutor = $documentStore->getRequestExecutor($this->databaseName);
        $this->noTracking = $options->isNoTracking();
    }

    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    public function getExternalState(){
        if(null === $this->externalState){
            $this->externalState = [];
        }
        return $this->externalState;
    }

    public function getConvetions():DocumentConventions {
        return $this->_requestExecutor->getConventions();
    }

    public function getDocumentId(object $instance):string|null {
        if(null === $instance) return null;
    }

    /***************** LifeCycle/UOW/Workflow ********************/
    public function prepareForSaveChanges():SaveChangesData {
        $result = new SaveChangesData($this);
        $deferredCommandsCount = count($this->deferredCommands);
        $this->prepareForEntitiesDeletion($result,null);
        $this->prepareForEntitiesPuts($result);
        $this->prepareForCreatingRevisionsFromIds($result);
        $this->prepareCompareExchangeEntities($result);
        return $result;
    }
    public function prepareForEntitiesPuts(SaveChangesData $result,array $changes):void {
    }
    public function prepareForEntitiesDeletion(SaveChangesData $result, ?array $changes=null):void { }
    public function prepareForCreatingRevisionsFromIds(SaveChangesData $result):void { }
    public function prepareCompareExchangeEntities(SaveChangesData $result):void { }
    /** *************************************************** **/

    private static function throwNoDatabase(){
        throw new IllegalStateException("Cannot open a Session without specifying a name of a database ".
            "to operate on. Database name can be passed as an argument when Session is".
            " being opened or default database can be defined using 'DocumentStore.setDatabase()' method");
    }
    public function getCurrentSessionNode():ServerNode {
        return $this->getSessionInfo()->getCurrentSessionNode($this->_requestExecutor);
    }

    public function getDocumentStore():IDocumentStore {
        return $this->_documentStore;
    }

    public function getRequestExecutor():RequestExecutor {
        return $this->_requestExecutor;
    }

    public function getSessionInfo(): SessionInfo {
        return $this->sessionInfo;
    }

    public function getOperations():OperationExecutor {
        if(null === $this->_operationExecutor){
            $this->_operationExecutor = new SessionOperationExecutor($this);
        }
    }

    public function getNumberOfRequests(): int
    {
        return $this->numberOfRequests;
    }

    /**
     * hold the data required to manage the data for RavenDB's Unit of Work
     */
    public function documentsByEntity():DocumentsByEntityHolder{
        return new DocumentsByEntityHolder();
    }

    /**
     * @return int
     */
    public function getDeferredCommands(): int
    {
        return count($this->deferredCommands);
    }
}