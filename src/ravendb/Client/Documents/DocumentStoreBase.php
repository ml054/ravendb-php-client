<?php

namespace RavenDB\Client\Documents;

use Exception;
use InvalidArgumentException;
use phpDocumentor\Reflection\Types\This;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Util\StringUtils;

/**
 * Class DocumentStoreBase
 * @package RavenDB\Client\Documents
 */
abstract class DocumentStoreBase implements IDocumentStore
{
    /**
     * @var bool
     */
    protected bool $disposed;
    /**
     * @var bool
     */
    protected bool $initialized = false;
    /**
     * @var string|array
     */
    protected string|array $urls;
    /**
     * @var string|null
     */
    protected ?string $database = null;

    /**
     * @return bool
     */
    public function isDisposed(): bool
    {
        return $this->disposed;
    }

    public function close()
    {
        // TODO: Implement close() method.
    }

    /**
     * @throws Exception
     */
    public function assertInitialized(): void
    {
        if (!$this->initialized) {
            throw new Exception("You cannot open a session or access the database commands
            before initializing the document store. Did you forget calling initialize()?");
        }
    }

    /**
     * @param string $property
     * @throws Exception
     */
    private function assertNotInitialized(string $property): void
    {
        if ($this->initialized) {
            throw new Exception("You cannot set $property after the document store has been initialized.");
        }
    }

    /**
     * Format the url values
     * @param array|string $values
     * @return void
     */
    public function setUrls(string|array $values): void
    {
        if (null === $values) throw new InvalidArgumentException("value cannot be null");

        $collect = $values;

        if (is_array($values)) {

            $collect = [];
            for ($i = 0; $i < count($values); $i++) {

                $values[$i] ?: throw new InvalidArgumentException("value cannot be null");
                // TODO: check URL to migrate to an Utils (UrlUtils::checkUrl()). based on occurrences
                if (false === filter_var($values[$i], FILTER_VALIDATE_URL)) {
                    throw new InvalidArgumentException("The url " . $values[$i] . " is not valid");
                }
                // TODO rtrim to StringUtils
                $collect[$i] = rtrim($values[$i], "/");
            }
        }

        $this->urls = $collect;
    }

    /**
     * @return array|string
     */
    public function getUrls(): array|string
    {
        return $this->urls;
    }

    /**
     * @return string
     */
    public function getDatabase(): ?string
    {
        return $this->database;
    }

    /**
     * Set the database instance
     * @param string|null $database
     * @return string|null
     */
    public function setDatabase(?string $database = null): ?string
    {
        return $this->database = $database;
    }

    /**
     * Ensure the resource is not closed
     * @return void
     * @throws Exception
     */
    protected function ensureNotClosed(): void
    {
        if ($this->disposed) {
            throw new Exception('The document store has already been disposed and cannot be used');
        }
    }

    /**
     * @param string $database
     * @param bool|null $secured
     * @param int|null $waitIndexingTimeout
     */
    public function getDocumentStore(string $database, ?bool $secured, ?int $waitIndexingTimeout)
    {
    }

    public function getEffectiveDatabase(string $database): string
    {
              return self::effectiveDatabase($database);
    }

    public static function effectiveDatabase(string $database, ?IDocumentStore $store = null): string
    {
        if (null === $database) {
            $database = $store->getDatabase();
        }
        /* TODO: CHECK improvement*/
        if (StringUtils::isNotBlank($database)) {
            return $database;
        }

        throw new InvalidArgumentException("Cannot determine database to operate on. " .
            "Please either specify 'database' directly as an action parameter " .
            "or set the default database to operate on using 'DocumentStore.setDatabase' method. " .
            "Did you forget to pass 'database' parameter? ");
    }

    private function runServer(bool $secured)
    {
        // TODO
    }
}

/**
 * package net.ravendb.client.documents;


/**
 *  Contains implementation of some IDocumentStore operations shared by DocumentStore implementations

public abstract class DocumentStoreBase implements IDocumentStore {
    /*
        private final List<EventHandler<BeforeStoreEventArgs>> onBeforeStore = new ArrayList<>();
        private final List<EventHandler<AfterSaveChangesEventArgs>> onAfterSaveChanges = new ArrayList<>();
        private final List<EventHandler<BeforeDeleteEventArgs>> onBeforeDelete = new ArrayList<>();
        private final List<EventHandler<BeforeQueryEventArgs>> onBeforeQuery = new ArrayList<>();
        private final List<EventHandler<SessionCreatedEventArgs>> onSessionCreated = new ArrayList<>();

        private final List<EventHandler<BeforeConversionToDocumentEventArgs>> onBeforeConversionToDocument = new ArrayList<>();
        private final List<EventHandler<AfterConversionToDocumentEventArgs>> onAfterConversionToDocument = new ArrayList<>();
        private final List<EventHandler<BeforeConversionToEntityEventArgs>> onBeforeConversionToEntity = new ArrayList<>();
        private final List<EventHandler<AfterConversionToEntityEventArgs>> onAfterConversionToEntity = new ArrayList<>();
        private final List<EventHandler<BeforeRequestEventArgs>> onBeforeRequest = new ArrayList<>();
        private final List<EventHandler<SucceedRequestEventArgs>> onSucceedRequest = new ArrayList<>();

        private final List<EventHandler<FailedRequestEventArgs>> onFailedRequest = new ArrayList<>();
        private final List<EventHandler<TopologyUpdatedEventArgs>> onTopologyUpdated = new ArrayList<>();

        protected DocumentStoreBase() {
            _subscriptions = new DocumentSubscriptions((DocumentStore)this);
        }

        public abstract void close();

        public abstract void addBeforeCloseListener(EventHandler<VoidArgs> event);

        public abstract void removeBeforeCloseListener(EventHandler<VoidArgs> event);

        public abstract void addAfterCloseListener(EventHandler<VoidArgs> event);

        public abstract void removeAfterCloseListener(EventHandler<VoidArgs> event);

        */
    /**
     * MIGRATED : EXPORTED TO PHP ENV.

    protected boolean disposed;


     * MIGRATED : EXPORTED TO PHP ENV.

    public boolean isDisposed() {
        return disposed;
    }

 * DUPLICATE : FUNCTIONS OF SAME NAME NOT ALLOWED WITHIN SAME OBJECT
 * */
/*
    public abstract IDatabaseChanges changes();

    public abstract IDatabaseChanges changes(String database);


    public abstract IDatabaseChanges changes(String database, String nodeTag);

    @Override
    public abstract CleanCloseable aggressivelyCacheFor(Duration cacheDuration);

    @Override
    public abstract CleanCloseable aggressivelyCacheFor(Duration cacheDuration, String database);

    @Override
    public abstract CleanCloseable aggressivelyCacheFor(Duration cacheDuration, AggressiveCacheMode mode);

    @Override
    public abstract CleanCloseable aggressivelyCacheFor(Duration cacheDuration, AggressiveCacheMode mode, String database);

    @Override
    public abstract CleanCloseable disableAggressiveCaching();

    @Override
    public abstract CleanCloseable disableAggressiveCaching(String database);

    public abstract String getIdentifier();

    public abstract void setIdentifier(String identifier);

    public abstract IDocumentStore initialize();

    public abstract IDocumentSession openSession();

    public abstract IDocumentSession openSession(String database);

    public abstract IDocumentSession openSession(SessionOptions sessionOptions);

    public void executeIndex(IAbstractIndexCreationTask task) {
        executeIndex(task, null);
    }

    public void executeIndex(IAbstractIndexCreationTask task, String database) {
        assertInitialized();
        task.execute(this, conventions, database);
    }

    @Override
    public void executeIndexes(List<IAbstractIndexCreationTask> tasks) {
        executeIndexes(tasks, null);
    }

    @Override
    public void executeIndexes(List<IAbstractIndexCreationTask> tasks, String database) {
        assertInitialized();
        IndexDefinition[] indexesToAdd = IndexCreation.createIndexesToAdd(tasks, conventions);

        maintenance()
                .forDatabase(getEffectiveDatabase(database))
                .send(new PutIndexesOperation(indexesToAdd));
    }

    private TimeSeriesOperations _timeSeriesOperation;

    public TimeSeriesOperations timeSeries() {
        if (_timeSeriesOperation == null) {
            _timeSeriesOperation = new TimeSeriesOperations(this);
        }

        return _timeSeriesOperation;
    }


    private DocumentConventions conventions;
*/
/**
 * Gets the conventions.

@Override
   /* public DocumentConventions getConventions() {
        if (conventions == null) {
            conventions = new DocumentConventions();
        }
        return conventions;
    }

    public void setConventions(DocumentConventions conventions) {
        assertNotInitialized("conventions");
        this.conventions = conventions;
    }*/

    /**
     * is this an array ?

     * MIGRATED : EXPORTED TO PHP ENV.
     * */
  /*  protected String[] urls = new String[0];

    public String[] getUrls() {
        return urls;
    }
*/
    /**
    public void setUrls(String[] value) {
    assertNotInitialized("urls");

    if (value == null) {
        throw new IllegalArgumentException("value is null");
    }
    /**
     * CHECK IF NOT BUG HERE : IN PHP IF VALUE IS NOT A STRING IN WILL RETURN EACH LETTER OF THE
    for (int i = 0; i < value.length; i++) {
        if (value[i] == null)
            throw new IllegalArgumentException("Urls cannot contain null");

        try {
            new URL(value[i]);
        } catch (MalformedURLException e) {
            throw new IllegalArgumentException("The url '" + value[i] + "' is not valid");
        }

            value[i] = StringUtils.stripEnd(value[i], "/");
        }

        this.urls = value;
    }

    /**
     * MIGRATED : EXPORTED TO PHP ENV.
    protected boolean initialized;

   /* private KeyStore _certificate;
    private char[] _certificatePrivateKeyPassword = "".toCharArray();
    private KeyStore _trustStore;

    public abstract BulkInsertOperation bulkInsert();

    public abstract BulkInsertOperation bulkInsert(String database);

    private final DocumentSubscriptions _subscriptions;

    public DocumentSubscriptions subscriptions() {
        return _subscriptions;
    }

    private ConcurrentMap<String, Long> _lastRaftIndexPerDatabase = new ConcurrentSkipListMap<>(String::compareToIgnoreCase);

    public Long getLastTransactionIndex(String database) {
        Long index = _lastRaftIndexPerDatabase.get(database);
        if (index == null || index == 0) {
            return null;
        }

        return index;
    }

    public void setLastTransactionIndex(String database, Long index) {
        if (index == null) {
            return;
        }

        _lastRaftIndexPerDatabase.compute(database, (__, initialValue) -> {
            if (initialValue == null) {
                return index;
            }
            return Math.max(initialValue, index);
        });
    }*/
    /**
     * MIGRATED : EXPORTED TO PHP ENV.
    protected void ensureNotClosed() {
        if (disposed) {
            throw new IllegalStateException("The document store has already been disposed and cannot be used");
        }
    }

     * MIGRATED : EXPORTED TO PHP ENV.
    public void assertInitialized() {
        if (!initialized) {
            throw new IllegalStateException("You cannot open a session or access the database commands before initializing the document store. Did you forget calling initialize()?");
        }
    }

     private void assertNotInitialized(String property) {
    if (initialized) {
        throw new IllegalStateException("You cannot set '" + property + "' after the document store has been initialized.");
    }
}

    public void addBeforeStoreListener(EventHandler<BeforeStoreEventArgs> handler) {
        this.onBeforeStore.add(handler);

    }
    public void removeBeforeStoreListener(EventHandler<BeforeStoreEventArgs> handler) {
        this.onBeforeStore.remove(handler);
    }

    public void addAfterSaveChangesListener(EventHandler<AfterSaveChangesEventArgs> handler) {
        this.onAfterSaveChanges.add(handler);
    }

    public void removeAfterSaveChangesListener(EventHandler<AfterSaveChangesEventArgs> handler) {
        this.onAfterSaveChanges.remove(handler);
    }

    public void addBeforeDeleteListener(EventHandler<BeforeDeleteEventArgs> handler) {
        this.onBeforeDelete.add(handler);
    }
    public void removeBeforeDeleteListener(EventHandler<BeforeDeleteEventArgs> handler) {
        this.onBeforeDelete.remove(handler);
    }

    public void addBeforeQueryListener(EventHandler<BeforeQueryEventArgs> handler) {
        this.onBeforeQuery.add(handler);
    }

    public void removeBeforeQueryListener(EventHandler<BeforeQueryEventArgs> handler) {
        this.onBeforeQuery.remove(handler);
    }

    public void addBeforeConversionToDocumentListener(EventHandler<BeforeConversionToDocumentEventArgs> handler) {
        this.onBeforeConversionToDocument.add(handler);
    }

    public void removeBeforeConversionToDocumentListener(EventHandler<BeforeConversionToDocumentEventArgs> handler) {
        this.onBeforeConversionToDocument.remove(handler);
    }

    public void addAfterConversionToDocumentListener(EventHandler<AfterConversionToDocumentEventArgs> handler) {
        this.onAfterConversionToDocument.add(handler);
    }

    public void removeAfterConversionToDocumentListener(EventHandler<AfterConversionToDocumentEventArgs> handler) {
        this.onAfterConversionToDocument.remove(handler);
    }

    public void addBeforeConversionToEntityListener(EventHandler<BeforeConversionToEntityEventArgs> handler) {
        this.onBeforeConversionToEntity.add(handler);
    }

    public void removeBeforeConversionToEntityListener(EventHandler<BeforeConversionToEntityEventArgs> handler) {
        this.onBeforeConversionToEntity.remove(handler);
    }

    public void addAfterConversionToEntityListener(EventHandler<AfterConversionToEntityEventArgs> handler) {
        this.onAfterConversionToEntity.add(handler);
    }

    public void removeAfterConversionToEntityListener(EventHandler<AfterConversionToEntityEventArgs> handler) {
        this.onAfterConversionToEntity.remove(handler);
    }

    public void addOnBeforeRequestListener(EventHandler<BeforeRequestEventArgs> handler) {
        assertNotInitialized("onSucceedRequest");
        this.onBeforeRequest.add(handler);
    }

    public void removeOnBeforeRequestListener(EventHandler<BeforeRequestEventArgs> handler) {
        assertNotInitialized("onSucceedRequest");
        this.onBeforeRequest.remove(handler);
    }

    public void addOnSucceedRequestListener(EventHandler<SucceedRequestEventArgs> handler) {
        assertNotInitialized("onSucceedRequest");
        this.onSucceedRequest.add(handler);
    }

    public void removeOnSucceedRequestListener(EventHandler<SucceedRequestEventArgs> handler) {
        assertNotInitialized("onSucceedRequest");
        this.onSucceedRequest.remove(handler);
    }

    public void addOnFailedRequestListener(EventHandler<FailedRequestEventArgs> handler) {
        assertNotInitialized("onFailedRequest");
        this.onFailedRequest.add(handler);
    }

    public void removeOnFailedRequestListener(EventHandler<FailedRequestEventArgs> handler) {
        assertNotInitialized("onFailedRequest");
        this.onFailedRequest.remove(handler);
    }

    public void addOnTopologyUpdatedListener(EventHandler<TopologyUpdatedEventArgs> handler) {
        assertNotInitialized("onTopologyUpdated");
        this.onTopologyUpdated.add(handler);
    }

    public void removeOnTopologyUpdatedListener(EventHandler<TopologyUpdatedEventArgs> handler) {
        assertNotInitialized("onTopologyUpdated");
        this.onTopologyUpdated.remove(handler);
    }

    * MIGRATED : EXPORTED TO PHP ENV.

    protected String database;


    @Override
    public String getDatabase() {
        return database;
    }


    public void setDatabase(String database) {
    assertNotInitialized("database");
    this.database = database;
}


    public KeyStore getCertificate() {
        return _certificate;
    }


    public void setCertificate(KeyStore certificate) {
    assertNotInitialized("certificate");
    _certificate = certificate;
}


    public char[] getCertificatePrivateKeyPassword() {
        return _certificatePrivateKeyPassword;
    }


    public void setCertificatePrivateKeyPassword(char[] certificatePrivateKeyPassword) {
    assertNotInitialized("certificatePrivateKeyPassword");
    _certificatePrivateKeyPassword = certificatePrivateKeyPassword;
}

    public KeyStore getTrustStore() {
        return _trustStore;
    }

    public void setTrustStore(KeyStore trustStore) {
    this._trustStore = trustStore;
}

    public abstract DatabaseSmuggler smuggler();

    public abstract RequestExecutor getRequestExecutor();

    public abstract RequestExecutor getRequestExecutor(String databaseName);

    @Override
    public CleanCloseable aggressivelyCache() {
        return aggressivelyCache(null);
    }

    @Override
    public CleanCloseable aggressivelyCache(String database) {
    return aggressivelyCacheFor(conventions.aggressiveCache().getDuration(), database);
}

    protected void registerEvents(InMemoryDocumentSessionOperations session) {
    for (EventHandler<BeforeStoreEventArgs> handler : onBeforeStore) {
        session.addBeforeStoreListener(handler);
    }

        for (EventHandler<AfterSaveChangesEventArgs> handler : onAfterSaveChanges) {
        session.addAfterSaveChangesListener(handler);
    }

        for (EventHandler<BeforeDeleteEventArgs> handler : onBeforeDelete) {
        session.addBeforeDeleteListener(handler);
    }

        for (EventHandler<BeforeQueryEventArgs> handler : onBeforeQuery) {
        session.addBeforeQueryListener(handler);
    }

        for (EventHandler<BeforeConversionToDocumentEventArgs> handler : onBeforeConversionToDocument) {
        session.addBeforeConversionToDocumentListener(handler);
    }

        for (EventHandler<AfterConversionToDocumentEventArgs> handler : onAfterConversionToDocument) {
        session.addAfterConversionToDocumentListener(handler);
    }

        for (EventHandler<BeforeConversionToEntityEventArgs> handler : onBeforeConversionToEntity) {
        session.addBeforeConversionToEntityListener(handler);
    }

        for (EventHandler<AfterConversionToEntityEventArgs> handler : onAfterConversionToEntity) {
        session.addAfterConversionToEntityListener(handler);
    }
    }

    public void registerEvents(RequestExecutor requestExecutor) {
    for (EventHandler<FailedRequestEventArgs> handler : onFailedRequest) {
        requestExecutor.addOnFailedRequestListener(handler);
    }

        for (EventHandler<TopologyUpdatedEventArgs> handler : onTopologyUpdated) {
        requestExecutor.addOnTopologyUpdatedListener(handler);
    }

        for (EventHandler<BeforeRequestEventArgs> handler : onBeforeRequest) {
        requestExecutor.addOnBeforeRequestListener(handler);
    }

        for (EventHandler<SucceedRequestEventArgs> handler : onSucceedRequest) {
        requestExecutor.addOnSucceedRequestListener(handler);
    }
    }

    protected void afterSessionCreated(InMemoryDocumentSessionOperations session) {
    EventHelper.invoke(onSessionCreated, this, new SessionCreatedEventArgs(session));
}

    public abstract MaintenanceOperationExecutor maintenance();

    public abstract OperationExecutor operations();

    public abstract CleanCloseable setRequestTimeout(Duration timeout);

    public abstract CleanCloseable setRequestTimeout(Duration timeout, String database);

    public String getEffectiveDatabase(String database) {
    return DocumentStoreBase.getEffectiveDatabase(this.database);
}

    public static String getEffectiveDatabase(IDocumentStore store, String database) {
    if (database == null) {
        database = store.getDatabase();
    }

    if (StringUtils.isNotBlank(database)) {
        return database;
    }

    throw new IllegalArgumentException("Cannot determine database to operate on. " +
        "Please either specify 'database' directly as an action parameter " +
        "or set the default database to operate on using 'DocumentStore.setDatabase' method. " +
        "Did you forget to pass 'database' parameter?");
}
}
*/