<?php


namespace RavenDB\Client\Documents\Session;


use InvalidArgumentException;
use RavenDB\Client\Documents\DocumentStoreBase;
use RavenDB\Client\Http\CurrentIndexAndNode;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Http\ServerNode;

class SessionInfo
{
    private int $_sessionId;
    private bool $_sessionIdUsed;
    private int $_loadBalancerContextSeed;
    private bool $_canUseLoadBalanceBehavior;
    private InMemoryDocumentSessionOperations $_session;
    private string $lastClusterTransactionIndex;
    private bool $noCaching;
    public CurrentIndexAndNode $result;

    public function __construct(InMemoryDocumentSessionOperations $session, SessionOptions $options,DocumentStoreBase $documentStore)
    {
        if(null === $documentStore){
            throw new InvalidArgumentException("DocumentStore cannot be null");
        }
        if(null === $session){
            throw new InvalidArgumentException("Session cannot be null");
        }
        $this->_session = $session;
        $this->_loadBalancerContextSeed = $session->getConvetions()->getLoadBalancerContextSeed();
        $this->setLastClusterTransactionIndex();
    }

    /**
     * @return string
     */
    public function getLastClusterTransactionIndex(): string
    {
        return $this->lastClusterTransactionIndex;
    }

    /**
     * @param string $lastClusterTransactionIndex
     */
    public function setLastClusterTransactionIndex(string $lastClusterTransactionIndex): void
    {
        $this->lastClusterTransactionIndex = $lastClusterTransactionIndex;
    }

    public function getCurrentSessionNode(RequestExecutor $requestExecutor):ServerNode {
        return $this->result->currentNode;
    }
}