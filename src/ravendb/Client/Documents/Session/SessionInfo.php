<?php


namespace RavenDB\Client\Documents\Session;


use InvalidArgumentException;
use RavenDB\Client\Documents\DocumentStoreBase;

class SessionInfo
{
    private int $_sessionId;
    private bool $_sessionIdUsed;
    private int $_loadBalancerContextSeed;
    private bool $_canUseLoadBalanceBehavior;
    private InMemoryDocumentSessionOperations $_session;
    private string $lastClusterTransactionIndex;
    private bool $noCaching;

    public function __construct(InMemoryDocumentSessionOperations $session, SessionOptions $options,DocumentStoreBase $documentStore)
    {
        if(null === $documentStore){
            throw new InvalidArgumentException("DocumentStore cannot be null");
        }
        if(null === $session){
            throw new InvalidArgumentException("Session cannot be null");
        }
        $this->_session = $session;
    }


}