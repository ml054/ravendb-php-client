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
Use RavenDB\Client\Documents\Naming as CaseConverter;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

/**
 * Class DatabaseRecord
 * @package RavenDB\Client\Serverwide
 * DatabaseRecord is a collection of database configurations.
 * @see https://ravendb.net/docs/article-page/4.2/java/client-api/operations/server-wide/create-database
 * Note : Object default value is null if not instantiated
 * @Annotation
 */
class DatabaseRecord
{
    // DATABASE_STATE prefixes are matching java's private DatabaseState $databaseState property in reference to RDBC-447;
    // no enum yet in php (may be in version 8.1) pending on vote. No migration to php 8.1 will be applied until Customer requires for
    public const DATABASE_STATE_NORMAL = "NORMAL";
    public const DATABASE_STATE_RESTORE_IN_PROGRESS = "RESTORE_IN_PROGRESS";
    private string $databaseName;
    private bool $disabled;
    private bool $encrypted;
    private string $etagForBackup;
    private array $deletionInProgress;
    private DatabaseTopology $topology;
    private ConflictSolver $conflictSolverConfig;
    private DocumentsCompressionConfiguration $documentsCompression;
    private array $sorters;
    private array $indexes;
    private array $indexesHistory;
    private array $autoIndexes;
    private array $settings;
    private RevisionsConfiguration $revisions;
    private TimeSeriesConfiguration $timeSeries;
    private RevisionsCollectionConfiguration $revisionsForConflicts;
    private ExpirationConfiguration $expiration;
    private RefreshConfiguration $refresh;
    private array $periodicBackups;
    private array $externalReplications;
    private array $sinkPullReplications;
    private array $hubPullReplications;
    private array $ravenConnectionStrings;
    private array $sqlConnectionStrings;
    private array $ravenEtls;
    private array $sqlEtls;
    private ClientConfiguration $client;
    private StudioConfiguration $studio;
    private float $truncatedClusterTransactionCommandsCount;
    private array $unusedDatabaseIds;

    /**
     * @return string|null
     */
    public function getDatabaseName(): ?string
    {
        return $this->databaseName ?? null;
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
        return $this->databaseState ?? self::DATABASE_STATE_NORMAL;
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

    /**
     * @return DocumentsCompressionConfiguration|null
     */
    public function getDocumentsCompression(): ?DocumentsCompressionConfiguration
    {
        return $this->documentsCompression ?? null;
    }

    /**
     * @param DocumentsCompressionConfiguration $documentsCompression
     */
    public function setDocumentsCompression(DocumentsCompressionConfiguration $documentsCompression): void
    {
        $this->documentsCompression = $documentsCompression;
    }

    /**
     * @return array|null
     */
    public function getSorters(): ?array
    {
        return $this->sorters ?? null;
    }

    /**
     * @param array $sorters
     */
    public function setSorters(array $sorters): void
    {
        $this->sorters = $sorters;
    }

    /**
     * @return array|null
     */
    public function getIndexes(): ?array
    {
        return $this->indexes ?? null;
    }

    /**
     * @param array $indexes
     */
    public function setIndexes(array $indexes): void
    {
        $this->indexes = $indexes;
    }

    /**
     * @return array|null
     */
    public function getIndexesHistory(): ?array
    {
        return $this->indexesHistory ?? null;
    }

    /**
     * @param array $indexesHistory
     */
    public function setIndexesHistory(array $indexesHistory): void
    {
        $this->indexesHistory = $indexesHistory;
    }

    /**
     * @return array|null
     */
    public function getAutoIndexes(): ?array
    {
        return $this->autoIndexes ?? null;
    }

    /**
     * @param array $autoIndexes
     */
    public function setAutoIndexes(array $autoIndexes): void
    {
        $this->autoIndexes = $autoIndexes;
    }

    /**
     * @return array|null
     */
    public function getSettings(): ?array
    {
        return $this->settings ?? null;
    }

    /**
     * @param array $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * @return array|null
     */
    public function getPeriodicBackups(): ?array
    {
        return $this->periodicBackups ?? null;
    }

    /**
     * @param array $periodicBackups
     */
    public function setPeriodicBackups(array $periodicBackups): void
    {
        $this->periodicBackups = $periodicBackups;
    }

    /**
     * @return array|null
     */
    public function getExternalReplications(): ?array
    {
        return $this->externalReplications ?? null;
    }

    /**
     * @param array $externalReplications
     */
    public function setExternalReplications(array $externalReplications): void
    {
        $this->externalReplications = $externalReplications;
    }

    /**
     * @return array|null
     */
    public function getSinkPullReplications(): ?array
    {
        return $this->sinkPullReplications ?? null;
    }

    /**
     * @param array $sinkPullReplications
     */
    public function setSinkPullReplications(array $sinkPullReplications): void
    {
        $this->sinkPullReplications = $sinkPullReplications;
    }

    /**
     * @return array|null
     */
    public function getHubPullReplications(): ?array
    {
        return $this->hubPullReplications ?? null;
    }

    /**
     * @param array $hubPullReplications
     */
    public function setHubPullReplications(array $hubPullReplications): void
    {
        $this->hubPullReplications = $hubPullReplications;
    }

    /**
     * @return array|null
     */
    public function getRavenConnectionStrings(): ?array
    {
        return $this->ravenConnectionStrings ?? null;
    }

    /**
     * @param array $ravenConnectionStrings
     */
    public function setRavenConnectionStrings(array $ravenConnectionStrings): void
    {
        $this->ravenConnectionStrings = $ravenConnectionStrings;
    }

    /**
     * @return array|null
     */
    public function getSqlConnectionStrings(): ?array
    {
        return $this->sqlConnectionStrings ?? null;
    }

    /**
     * @param array $sqlConnectionStrings
     */
    public function setSqlConnectionStrings(array $sqlConnectionStrings): void
    {
        $this->sqlConnectionStrings = $sqlConnectionStrings;
    }

    /**
     * @return array|null
     */
    public function getRavenEtls(): ?array
    {
        return $this->ravenEtls ?? null;
    }

    /**
     * @param array $ravenEtls
     */
    public function setRavenEtls(array $ravenEtls): void
    {
        $this->ravenEtls = $ravenEtls;
    }

    /**
     * @return array|null
     */
    public function getSqlEtls(): ?array
    {
        return $this->sqlEtls ?? null;
    }

    /**
     * @param array $sqlEtls
     */
    public function setSqlEtls(array $sqlEtls): void
    {
        $this->sqlEtls = $sqlEtls;
    }

    /**
     * @return float|null
     */
    public function getTruncatedClusterTransactionCommandsCount(): ?float
    {
        return $this->truncatedClusterTransactionCommandsCount ?? null;
    }

    /**
     * @param float $truncatedClusterTransactionCommandsCount
     */
    public function setTruncatedClusterTransactionCommandsCount(float $truncatedClusterTransactionCommandsCount): void
    {
        $this->truncatedClusterTransactionCommandsCount = $truncatedClusterTransactionCommandsCount;
    }

    /**
     * @return array|null
     */
    public function getUnusedDatabaseIds(): ?array
    {
        return $this->unusedDatabaseIds ?? null;
    }

    /**
     * @param array $unusedDatabaseIds
     */
    public function setUnusedDatabaseIds(array $unusedDatabaseIds): void
    {
        $this->unusedDatabaseIds = $unusedDatabaseIds;
    }

}