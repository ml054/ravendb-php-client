<?php


namespace RavenDB\Client\Documents\Session;


use Ramsey\Uuid\Uuid;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeStoreEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\InMemorySessionDispatcher;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;
abstract class InMemoryDocumentSessionOperations implements Closable
{
    use InMemorySessionDispatcher;
    protected RequestExecutor $_requestExecutor;
    private OperationExecutor $_operationExecutor;
    private const TRANSACTION_MODE_SINGLE_NODE = "SINGLE_NODE"; // NO ENUM YET IN PHP
    private const TRANSACTION_MODE_CLUSTER_WIDE = "CLUSTER_WIDE"; // NO ENUM YET IN PHP
    protected array $pendingLazyOperations;
    protected array $onEvaluateLazy;
    private int $_instancesCounter;
    protected bool $generateDocumentKeysOnStore=true;
    protected SessionInfo $sessionInfo;
    protected int $_hash;
    private bool $_isDisposed;
    //    protected final ObjectMapper mapper = JsonExtensions.getDefaultMapper(); TODO USE THE PHP MATTER
    private array $onBeforeStore;
    private array $onAfterSaveChanges;
    private array $onBeforeDelete;
    private array $onBeforeQuery;
    private Uuid $id;
    private array $onBeforeConversionToDocument;
    private array $onAfterConversionToDocument;
    private array $onBeforeConversionToEntity;
    private array $onAfterConversionToEntity;

    /**
     * @throws \Exception
     */
    public function addBeforeStoreListener(BeforeStoreEventArgs $handler){
        $this->add($handler,'onBeforeStore');
    }

}