<?php

namespace RavenDB\Client\Documents\Operations;
use InvalidArgumentException;
use RavenDB\Client\Documents\DocumentStore;
use RavenDB\Client\Http\ClusterRequestExecutor;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;
use RavenDB\Client\Serverwide\Operations\IServerOperation;
class ServerOperationExecutor implements Closable
{
    private ?string $_nodeTag=null;
    private DocumentStore $_store;
    private ?RequestExecutor $_requestExecutor=null; // replaced from ClusterRequestExecutor
    private ?RequestExecutor $_initialRequestExecutor;

    public function __construct(?DocumentStore $store,RequestExecutor $requestExecutor)
    {
        if (null === $store) {
            throw new InvalidArgumentException("Store cannot be null");
        }
        $this->_store = $store;
        $this->_requestExecutor = $requestExecutor;
    }

    public function send(IServerOperation $operation)
    {
        // TODO: CHECK WITH MARCIN IF INTERFACE SHOULD BE CHECKED
        $command = $operation->getCommand($this->_requestExecutor->getConventions());
        $this->_requestExecutor->execute($command);
    }

    public function close()
    {
        // TODO: Implement close() method.
    }

}

/*
 *
public class ServerOperationExecutor implements CleanCloseable {

    public ServerOperationExecutor(DocumentStore store) {
        this(store, createRequestExecutor(store), null, new ConcurrentSkipListMap<>(String::compareToIgnoreCase), null);
    }

    private ServerOperationExecutor(DocumentStore store, ClusterRequestExecutor requestExecutor, ClusterRequestExecutor initialRequestExecutor,
                                    ConcurrentMap<String, ServerOperationExecutor> cache, String nodeTag) {
        if (store == null) {
            throw new IllegalArgumentException("Store cannot be null");
        }

        if (requestExecutor == null) {
            throw new IllegalArgumentException("RequestExecutor cannot be null");
        }

        _store = store;
        _requestExecutor = requestExecutor;
        _initialRequestExecutor = initialRequestExecutor;
        _nodeTag = nodeTag;
        _cache = cache;

        store.registerEvents(_requestExecutor);

        if (_nodeTag == null) {
            store.addAfterCloseListener((sender, event) -> _requestExecutor.close());
        }
    }

    public ServerOperationExecutor forNode(String nodeTag) {
        if (StringUtils.isBlank(nodeTag)) {
            throw new IllegalArgumentException("Value cannot be null or whitespace.");
        }

        if ((nodeTag == null && _nodeTag == null) || _nodeTag.equalsIgnoreCase(nodeTag)) {
            return this;
        }

        if (_store.getConventions().isDisableTopologyUpdates()) {
            throw new IllegalStateException("Cannot switch server operation executor, because Conventions.isDisableTopologyUpdates() is set to 'true'");
        }

        return _cache.computeIfAbsent(nodeTag, tag -> {
            ClusterRequestExecutor requestExecutor = ObjectUtils.firstNonNull(_initialRequestExecutor, _requestExecutor);
            Topology topology = getTopology(requestExecutor);

            ServerNode node = topology
                    .getNodes()
                    .stream()
                    .filter(x -> tag.equalsIgnoreCase(x.getClusterTag()))
                    .findFirst()
                    .orElse(null);

            if (node == null) {
                String availableNodes = topology.getNodes()
                        .stream()
                        .map(x -> x.getClusterTag())
                        .collect(Collectors.joining(", "));

                throw new IllegalStateException("Could not find node '" + tag + "' in the topology. Available nodes: " + availableNodes);
            }

            ClusterRequestExecutor clusterExecutor = ClusterRequestExecutor.createForSingleNode(node.getUrl(),
                    _store.getCertificate(),
                    _store.getCertificatePrivateKeyPassword(),
                    _store.getTrustStore(),
                    _store.getExecutorService());

            return new ServerOperationExecutor(_store, clusterExecutor, requestExecutor, _cache, node.getClusterTag());
        });
    }


    public Operation sendAsync(IServerOperation<OperationIdResult> operation) {
        RavenCommand<OperationIdResult> command = operation.getCommand(_requestExecutor.getConventions());

        _requestExecutor.execute(command);
        return new ServerWideOperation(_requestExecutor,
                _requestExecutor.getConventions(),
                command.getResult().getOperationId(),
                ObjectUtils.firstNonNull(command.getSelectedNodeTag(), command.getResult().getOperationNodeTag()));
    }

    @Override
    public void close() {
        if (_nodeTag != null) {
            return;
        }

        if (_requestExecutor != null) {
            _requestExecutor.close();
        }

        ConcurrentMap<String, ServerOperationExecutor> cache = _cache;
        if (cache != null) {
            for (Map.Entry<String, ServerOperationExecutor> kvp : cache.entrySet()) {
                ClusterRequestExecutor requestExecutor = kvp.getValue()._requestExecutor;
                if (requestExecutor != null) {
                    requestExecutor.close();
                }
            }

            cache.clear();
        }
    }

    private Topology getTopology(ClusterRequestExecutor requestExecutor) {
        Topology topology = null;
        try {
            topology = requestExecutor.getTopology();
            if (topology == null) {
                // a bit rude way to make sure that topology has been refreshed
                // but it handles a case when first topology update failed

                GetBuildNumberOperation operation = new GetBuildNumberOperation();
                RavenCommand<BuildNumber> command = operation.getCommand(requestExecutor.getConventions());
                requestExecutor.execute(command);

                topology = requestExecutor.getTopology();
            }
        } catch (Exception e) {
            // ignored
        }

        if (topology == null) {
            throw new IllegalStateException("Could not fetch the topology.");
        }

        return topology;
    }

    private static ClusterRequestExecutor createRequestExecutor(DocumentStore store) {
        return store.getConventions().isDisableTopologyUpdates() ?
                ClusterRequestExecutor.createForSingleNode(store.getUrls()[0], store.getCertificate(), store.getCertificatePrivateKeyPassword(), store.getTrustStore(), store.getExecutorService(), store.getConventions()) :
                ClusterRequestExecutor.create(store.getUrls(), store.getCertificate(), store.getCertificatePrivateKeyPassword(), store.getTrustStore(), store.getExecutorService(), store.getConventions());
    }

}

 * */