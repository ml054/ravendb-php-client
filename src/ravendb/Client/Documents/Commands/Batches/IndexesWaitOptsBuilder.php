<?php

namespace RavenDB\Client\Documents\Commands\Batches;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Documents\Session\InMemoryDocumentSessionOperations;

class IndexesWaitOptsBuilder
{
    private $session;

    public function __construct(InMemoryDocumentSessionOperations $session)
    {

        $this->session = $session;
        $this->session->_saveChangesOptions = null;
    }
    // IF NEEDED PUBLIC GET METHODS CAN BE MAKE READ ONLY VIA ANNOTATIONS
    public function getOptions():BatchOptions {
        if(null === $this->session->_saveChangesOptions) {
            $this->session->_saveChangesOptions = new BatchOptions();
        }
        if(null === $this->session->_saveChangesOptions->getIndexOptions()) {
            $this->session->_saveChangesOptions->setIndexOptions(new IndexBatchOptions());
        }
        return $this->session->_saveChangesOptions;
    }

    public function withTimeout(int $timeout ):IndexesWaitOptsBuilder {
        $this->getOptions()->getIndexOptions()->setWaitForIndexesTimeout($timeout);
        return $this;
    }

    public function throwOnTimeout (bool $shouldThrow): IndexesWaitOptsBuilder {
        $this->getOptions()->getIndexOptions()->setThrowOnTimeoutInWaitForIndexes($shouldThrow);
        return $this;
    }

    public function waitForIndexes(ArrayCollection $indexes):IndexesWaitOptsBuilder {
        $this->getOptions()->getIndexOptions()->setWaitForSpecificIndexes($indexes);
        return $this;
    }
}
