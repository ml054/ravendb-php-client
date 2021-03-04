<?php


namespace RavenDB\Client\Documents\Session;


use RavenDB\Client\Http\RequestExecutor;

class SessionOptions
{
    private string $database;
    private bool $noTracking;
    private bool $noCaching;
    private RequestExecutor $requestExecutor;
    private TransactionMode $transactionMode;

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function setDatabase(string $database): void
    {
        $this->database = $database;
    }

    public function getRequestExecutor(): RequestExecutor
    {
        return $this->requestExecutor;
    }

    public function setRequestExecutor(RequestExecutor $requestExecutor): RequestExecutor
    {
        return $this->requestExecutor = $requestExecutor;
    }

    public function isNoTracking(): bool
    {
        return $this->noTracking;
    }

    public function setNoTracking(bool $noTracking): void
    {
        $this->noTracking = $noTracking;
    }

    public function isNoCaching(): bool
    {
        return $this->noCaching;
    }

    public function setNoCaching(bool $noCaching): void
    {
        $this->noCaching = $noCaching;
    }

    public function getTransactionMode(): TransactionMode {
        return $this->transactionMode;
    }
    public function setTransactionMode(TransactionMode $transactionMode): void {
        $this->transactionMode = $transactionMode;
    }

}