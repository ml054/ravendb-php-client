<?php

namespace RavenDB\Client\Serverwide;

/**
 * Class DatabaseRecord
 * @package RavenDB\Client\Serverwide
 * DatabaseRecord is a collection of database configurations.
 * @see https://ravendb.net/docs/article-page/4.2/java/client-api/operations/server-wide/create-database
 */
class DatabaseRecord
{
    const DatabaseStateStatus=["NORMAL","RESTORE_IN_PROGRESS"];
    private string $databaseName;
    private bool $disabled;
    private bool $encrypted;
    private float $etagForBackup;
  //  private $databaseStatus = self::DatabaseStateStatus ;
  //  private DatabaseTopology $topology;
   /* private ConflictSolver $conflictSolverConfig;
    private DocumentsCompressionConfiguration $documentsCompression;
    private RevisionsConfiguration $revisions;
    private TimeSeriesConfiguration $timeSeries;
    private RevisionsCollectionConfiguration $revisionsForConflicts;
    private ExpirationConfiguration $expiration;
    private RefreshConfiguration $refresh;
    private ClientConfiguration $client;
    private StudioConfiguration $studio;*/
    private string $truncatedClusterTransactionCommandsCount;
    //// BEGIN: phpDocumentor annotation properties
    private string $deletionInProgress;

    /**
     * @return mixed
     */
    public function getDatabaseName(): mixed
    {
        return $this->databaseName;
    }


    public function setDatabaseName(string $databaseName)
    {
        $this->databaseName = $databaseName;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): void
    {
        $this->disabled = $disabled;
    }


    public function isEncrypted(): bool
    {
        return $this->encrypted;
    }


    public function setEncrypted(bool $encrypted): void
    {
        $this->encrypted = $encrypted;
    }


    public function getEtagForBackup(): float
    {
        return $this->etagForBackup;
    }


    public function setEtagForBackup(float $etagForBackup): void
    {
        $this->etagForBackup = $etagForBackup;
    }
}