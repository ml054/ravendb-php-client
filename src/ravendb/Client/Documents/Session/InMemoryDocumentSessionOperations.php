<?php


namespace RavenDB\Client\Documents\Session;


use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;
abstract class InMemoryDocumentSessionOperations implements Closable
{
    protected RequestExecutor $_requestExecutor;
    private OperationExecutor $_operationExecutor;
    protected array $pendingLazyOperations;
    protected array $onEvaluateLazy;
    private int $_instancesCounter; // TODO CHECK CONVERTION WITH TECH TEAM  private static final AtomicInteger _instancesCounter = new AtomicInteger();
    protected bool $generateDocumentKeysOnStore=true;
    protected SessionInfo $sessionInfo;
    protected int $_hash;

    private array $onBeforeStore;
    private array $onAfterSaveChanges;
    private array $onBeforeDelete;
    private array $onBeforeQuery;

    private array $onBeforeConversionToDocument;
    private array $onAfterConversionToDocument;
    private array $onBeforeConversionToEntity;
    private array $onAfterConversionToEntity;

    public function addBeforeStoreListener(BeforeStoreEventArgs $handler){

    }

}