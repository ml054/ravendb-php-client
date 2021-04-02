<?php

namespace RavenDB\Client\Serverwide;

use RavenDB\Client\Documents\Operations\Configuration\ClientConfiguration;
use RavenDB\Client\Documents\Operations\Configuration\StudioConfiguration;
use RavenDB\Client\Documents\Operations\Expiration\ExpirationConfiguration;
use RavenDB\Client\Documents\Operations\Refresh\RefreshConfiguration;
use RavenDB\Client\Documents\Operations\Revisions\RevisionsCollectionConfiguration;
use RavenDB\Client\Documents\Operations\Revisions\RevisionsConfiguration;
use RavenDB\Client\Documents\Operations\TimeSeries\TimeSeriesConfiguration;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Class DatabaseRecord
 * @package RavenDB\Client\Serverwide
 * DatabaseRecord is a collection of database configurations.
 * @see https://ravendb.net/docs/article-page/4.2/java/client-api/operations/server-wide/create-database
 * Note : Object default value is null
 */
class DatabaseRecord
{
    public const DATABASE_STATUS_NORMAL = "NORMAL";
    public const DATABASE_STATUS_RESTORE_IN_PROGRESS = "RESTORE_IN_PROGRESS";
    #[SerializedName("DatabaseName")]
    private string $databaseName;
    private bool $disabled;
    private bool $encrypted;
    private string $etagForBackup;
    private ConflictSolver $conflictSolverConfig;
    private RevisionsConfiguration $revisions;
    private TimeSeriesConfiguration $timeSeries;
    private RevisionsCollectionConfiguration $revisionsForConflicts;
    private ExpirationConfiguration $expiration;
    private RefreshConfiguration $refresh;
    private ClientConfiguration $client;
    private StudioConfiguration $studio;
    private string $truncatedClusterTransactionCommandsCount;
    private array $deletionInProgress;
    private string $databaseState;
    private DatabaseTopology $topology;
    private DocumentsCompressionConfiguration $documentsCompression;
    /**
     * @return mixed
     */
    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }


    public function setDatabaseName(string $databaseName)
    {
        $this->databaseName = $databaseName;
    }

    public function isDisabled(): ?bool
    {
        return $this->disabled ?? false;
    }

    public function setDisabled(bool $disabled): void
    {
        $this->disabled = $disabled;
    }


    public function isEncrypted(): ?bool
    {
        return $this->encrypted ?? false;
    }


    public function setEncrypted(bool $encrypted): void
    {
        $this->encrypted = $encrypted;
    }


    public function getEtagForBackup(): ?float
    {
        return $this->etagForBackup ?? null;
    }


    public function setEtagForBackup(float $etagForBackup): void
    {
        $this->etagForBackup = $etagForBackup;
    }

    /**
     * @return string
     */
    public function getDatabaseState(): string
    {
        return $this->databaseState ?? self::DATABASE_STATUS_NORMAL;
    }

    /**
     * @param string $databaseState
     */
    public function setDatabaseState(string $databaseState): void
    {
        $this->databaseState = $databaseState;
    }



    /**
     * @return array
     */
    public function getDeletionInProgress(): ?array
    {
        return $this->deletionInProgress ?? null;
    }

    /**
     * @param array $deletionInProgress
     */
    public function setDeletionInProgress(array $deletionInProgress): void
    {
        $this->deletionInProgress = $deletionInProgress;
    }

    /**
     * @return DatabaseTopology|null
     * IF OBJECT NULL IF NOT SET
     */
    public function getTopology(): ?DatabaseTopology
    {
        return $this->topology ?? null;
    }

    /**
     * @param DatabaseTopology $topology
     */
    public function setTopology(DatabaseTopology $topology): void
    {
        $this->topology = $topology;
    }

    /**
     * @return ConflictSolver|null
     */
    public function getConflictSolverConfig(): ?ConflictSolver
    {
        return $this->conflictSolverConfig ?? null;
    }

    /**
     * @param ConflictSolver $conflictSolverConfig
     */
    public function setConflictSolverConfig(ConflictSolver $conflictSolverConfig): void
    {
        $this->conflictSolverConfig = $conflictSolverConfig;
    }

    /**
     * @return RevisionsConfiguration|null
     */
    public function getRevisions(): ?RevisionsConfiguration
    {
        return $this->revisions ?? null;
    }

    /**
     * @param RevisionsConfiguration $revisions
     */
    public function setRevisions(RevisionsConfiguration $revisions): void
    {
        $this->revisions = $revisions;
    }

    /**
     * @return TimeSeriesConfiguration|null
     */
    public function getTimeSeries(): ?TimeSeriesConfiguration
    {
        return $this->timeSeries ?? null;
    }

    /**
     * @param TimeSeriesConfiguration $timeSeries
     */
    public function setTimeSeries(TimeSeriesConfiguration $timeSeries): void
    {
        $this->timeSeries = $timeSeries;
    }

    /**
     * @return RevisionsCollectionConfiguration|null
     */
    public function getRevisionsForConflicts(): ?RevisionsCollectionConfiguration
    {
        return $this->revisionsForConflicts ?? null;
    }

    /**
     * @param RevisionsCollectionConfiguration $revisionsForConflicts
     */
    public function setRevisionsForConflicts(RevisionsCollectionConfiguration $revisionsForConflicts): void
    {
        $this->revisionsForConflicts = $revisionsForConflicts;
    }

    /**
     * @return ExpirationConfiguration|null
     */
    public function getExpiration(): ?ExpirationConfiguration
    {
        return $this->expiration ?? null;
    }

    /**
     * @param ExpirationConfiguration $expiration
     */
    public function setExpiration(ExpirationConfiguration $expiration): void
    {
        $this->expiration = $expiration;
    }

    /**
     * @return RefreshConfiguration|null
     */
    public function getRefresh(): ?RefreshConfiguration
    {
        return $this->refresh ?? null;
    }

    /**
     * @param RefreshConfiguration $refresh
     */
    public function setRefresh(RefreshConfiguration $refresh): void
    {
        $this->refresh = $refresh;
    }

    /**
     * @return ClientConfiguration|null
     */
    public function getClient(): ?ClientConfiguration
    {
        return $this->client ?? null;
    }

    /**
     * @param ClientConfiguration $client
     */
    public function setClient(ClientConfiguration $client): void
    {
        $this->client = $client;
    }

    /**
     * @return StudioConfiguration|null
     */
    public function getStudio(): ?StudioConfiguration
    {
        return $this->studio ?? null;
    }

    /**
     * @param StudioConfiguration $studio
     */
    public function setStudio(StudioConfiguration $studio): void
    {
        $this->studio = $studio;
    }


}