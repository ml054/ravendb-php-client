<?php

namespace RavenDB\Client\Serverwide;
/// TODO: COMPLETE THE IMPLEMENTATION. JUST GENERATED FOR THE PURPOSE OF DATABASERECORD
/// ADD PHPDOC ANNOTATIONS
class DatabaseTopology
{
    private array  $members;
    private array $promotables;
    private array  $rehabs;

    private array $predefinedMentors;
    private array $demotionReasons;
    private array $promotablesStatus;
    private int $replicationFactor;
    private bool $dynamicNodesDistribution;
    private LeaderStamp $stamp;
    private string $databaseTopologyIdBase64;
    private array  $priorityOrder;
    public function getAllNodes(){
    }

    /**
     * @return array
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param array $members
     */
    public function setMembers(array $members): void
    {
        $this->members = $members;
    }

    /**
     * @return array
     */
    public function getPromotables(): array
    {
        return $this->promotables;
    }

    /**
     * @param array $promotables
     */
    public function setPromotables(array $promotables): void
    {
        $this->promotables = $promotables;
    }

    /**
     * @return array
     */
    public function getRehabs(): array
    {
        return $this->rehabs;
    }

    /**
     * @param array $rehabs
     */
    public function setRehabs(array $rehabs): void
    {
        $this->rehabs = $rehabs;
    }

    /**
     * @return array
     */
    public function getPredefinedMentors(): array
    {
        return $this->predefinedMentors;
    }

    /**
     * @param array $predefinedMentors
     */
    public function setPredefinedMentors(array $predefinedMentors): void
    {
        $this->predefinedMentors = $predefinedMentors;
    }

    /**
     * @return array
     */
    public function getDemotionReasons(): array
    {
        return $this->demotionReasons;
    }

    /**
     * @param array $demotionReasons
     */
    public function setDemotionReasons(array $demotionReasons): void
    {
        $this->demotionReasons = $demotionReasons;
    }

    /**
     * @return array
     */
    public function getPromotablesStatus(): array
    {
        return $this->promotablesStatus;
    }

    /**
     * @param array $promotablesStatus
     */
    public function setPromotablesStatus(array $promotablesStatus): void
    {
        $this->promotablesStatus = $promotablesStatus;
    }

    /**
     * @return int
     */
    public function getReplicationFactor(): int
    {
        return $this->replicationFactor;
    }

    /**
     * @param int $replicationFactor
     */
    public function setReplicationFactor(int $replicationFactor): void
    {
        $this->replicationFactor = $replicationFactor;
    }

    /**
     * @return bool
     */
    public function isDynamicNodesDistribution(): bool
    {
        return $this->dynamicNodesDistribution;
    }

    /**
     * @param bool $dynamicNodesDistribution
     */
    public function setDynamicNodesDistribution(bool $dynamicNodesDistribution): void
    {
        $this->dynamicNodesDistribution = $dynamicNodesDistribution;
    }

    /**
     * @return LeaderStamp
     */
    public function getStamp(): LeaderStamp
    {
        return $this->stamp;
    }

    /**
     * @param LeaderStamp $stamp
     */
    public function setStamp(LeaderStamp $stamp): void
    {
        $this->stamp = $stamp;
    }

    /**
     * @return string
     */
    public function getDatabaseTopologyIdBase64(): string
    {
        return $this->databaseTopologyIdBase64;
    }

    /**
     * @param string $databaseTopologyIdBase64
     */
    public function setDatabaseTopologyIdBase64(string $databaseTopologyIdBase64): void
    {
        $this->databaseTopologyIdBase64 = $databaseTopologyIdBase64;
    }

    /**
     * @return array
     */
    public function getPriorityOrder(): array
    {
        return $this->priorityOrder;
    }

    /**
     * @param array $priorityOrder
     */
    public function setPriorityOrder(array $priorityOrder): void
    {
        $this->priorityOrder = $priorityOrder;
    }


}
