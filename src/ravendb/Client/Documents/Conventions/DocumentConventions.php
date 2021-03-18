<?php

namespace RavenDB\Client\Documents\Conventions;

use RavenDB\Client\Documents\Operations\Configuration\ClientConfiguration;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Http\LoadBalanceBehavior;
use RavenDB\Client\Http\ReadBalanceBehavior;

class DocumentConventions
{
    private ?int $_maxHttpCacheSize=null;
    private ?array $_listOfRegisteredIdConventions = null;
    private ?bool $_frozen = null;
    private ?ClientConfiguration $_originalConfiguration = null;
    private ?bool $_saveEnumsAsIntegers = null;
    private ?bool $_identityPartsSeparator = null;
    private ?bool $_disableTopologyUpdates = null;
    private ?bool $_useOptimisticConcurrency = null;
    private ?bool $_throwIfQueryPageSizeIsNotSet = null;
    private ?int $_maxNumberOfRequestsPerSession = null;
    private ?int $_requestTimeout = null;
    private ?int $_firstBroadcastAttemptTimeout = null;
    private ?int $_secondBroadcastAttemptTimeout = null;
    private ?LoadBalanceBehavior $_loadBalanceBehavior = null;
    private ?ReadBalanceBehavior $_readBalanceBehavior = null;
    private ObjectMapper $_entityMapper; /*TODO : IMPORT THE CLASS*/
    private ?bool $_useCompression=null;

    public function getMaxHttpCacheSize(): ?int
    {
        return $this->_maxHttpCacheSize;
    }

    public function getRequestTimeout(): ?int
    {
        return $this->_requestTimeout;
    }

    public function setRequestTimeout(int $requestTimeout)
    {
        $this->assertNotFrozen();
        $this->_requestTimeout = $requestTimeout;
    }

    public function setMaxHttpCacheSize(int $maxHttpCacheSize)
    {
        $this->assertNotFrozen();
        $this->_maxHttpCacheSize = $maxHttpCacheSize;
    }

    // TODO: REFACTOR
    public function clone(): DocumentConventions
    {
        $cloned = clone new DocumentConventions();
        $cloned->_listOfRegisteredIdConventions = $this->_listOfRegisteredIdConventions;
        $cloned->_frozen = $this->_frozen;
        $cloned->_originalConfiguration = $this->_originalConfiguration;
        $cloned->_saveEnumsAsIntegers = $this->_saveEnumsAsIntegers;
        $cloned->_identityPartsSeparator = $this->_identityPartsSeparator;
        $cloned->_disableTopologyUpdates = $this->_disableTopologyUpdates;
        $cloned->_useOptimisticConcurrency = $this->_useOptimisticConcurrency;
        $cloned->_throwIfQueryPageSizeIsNotSet = $this->_throwIfQueryPageSizeIsNotSet;
        $cloned->_maxNumberOfRequestsPerSession = $this->_maxNumberOfRequestsPerSession;
        $cloned->_readBalanceBehavior = $this->_readBalanceBehavior;
        $cloned->_loadBalanceBehavior = $this->_loadBalanceBehavior;
        $cloned->_maxHttpCacheSize = $this->_maxHttpCacheSize;
        $cloned->_useCompression = $this->_useCompression;
        return $cloned;
    }

    public function freeze(): void
    {
        $this->_frozen = true;
    }

    /**
     * @throws IllegalStateException
     */
    private function assertNotFrozen(): void
    {
        if ($this->_frozen) {
            throw new IllegalStateException("Conventions has been frozen after documentStore.initialize() and no changes can be applied to them");
        }
    }

    public function setSecondBroadcastAttemptTimeout(int $secondBroadcastAttemptTimeout): void
    {
        try {
            $this->assertNotFrozen();
        } catch (IllegalStateException $e) {
        }
        $this->_secondBroadcastAttemptTimeout = $secondBroadcastAttemptTimeout;
    }

    public function getSecondBroadcastAttemptTimeout(): ?int
    {
        return $this->_secondBroadcastAttemptTimeout;
    }

    public function getFirstBroadcastAttemptTimeout(): ?int
    {
        return $this->_firstBroadcastAttemptTimeout;
    }

    public function setFirstBroadcastAttemptTimeout(?int $firstBroadcastAttemptTimeout): void
    {
        try {
            $this->assertNotFrozen();
        } catch (IllegalStateException $e) {
        }
        $this->_firstBroadcastAttemptTimeout = $firstBroadcastAttemptTimeout;
    }
}
