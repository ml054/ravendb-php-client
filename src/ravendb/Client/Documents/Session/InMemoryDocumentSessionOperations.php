<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace RavenDB\Client\Documents\Session;

use Ramsey\Uuid\Uuid;
use RavenDB\Client\Documents\DocumentStoreBase;
use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\AfterConversionToDocumentEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\AfterConversionToEntityEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\AfterSaveChangesEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeConversionToDocumentEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeConversionToEntityEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeDeleteEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeQueryEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\EventArgs\BeforeStoreEventArgs;
use RavenDB\Client\Documents\Session\EventDispatcher\InMemorySessionDispatcher;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\EventNameHolder;
use RavenDB\Client\Util\ObjectUtils;
use RavenDB\Client\Util\StringUtils;

// JAVA VERSION IS BUILDING A LIST WITH add/remove OF HANDLERS.
// PHP APROACH IS TO CREATE InMemorySession SUBSCRIBER READY TO DO THE SAME JOB : Trigger, add/remove. SUBJECT TO IMPROVEMENT

abstract class InMemoryDocumentSessionOperations implements Closable
{
    use InMemorySessionDispatcher;
    use EventNameHolder;
    const ADD=".add";
    const REMOVE=".remove";
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
    protected $mapper; // ObjectMapper
    private array $onBeforeStore;
    private array $onAfterSaveChanges;
    private array $onBeforeDelete;
    private array $onBeforeQuery;
    private string $id;
    private array $onBeforeConversionToDocument;
    private array $onAfterConversionToDocument;
    private array $onBeforeConversionToEntity;
    private array $onAfterConversionToEntity;
    private string $databaseName;
    protected DocumentStoreBase $_documentStore;
    public bool $noTracking;

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

    private static function throwNoDatabase(){
        throw new IllegalStateException("Cannot open a Session without specifying a name of a database ".
            "to operate on. Database name can be passed as an argument when Session is".
            " being opened or default database can be defined using 'DocumentStore.setDatabase()' method");
    }
}