<?php

namespace RavenDB\Client\Serverwide;

class LeaderStamp
{
    private float $index;
    private float $term;
    private float $leadersTicks;

    /**
     * @return float
     */
    public function getIndex(): float
    {
        return $this->index;
    }

    /**
     * @param float $index
     */
    public function setIndex(float $index): void
    {
        $this->index = $index;
    }

    /**
     * @return float
     */
    public function getTerm(): float
    {
        return $this->term;
    }

    /**
     * @param float $term
     */
    public function setTerm(float $term): void
    {
        $this->term = $term;
    }

    /**
     * @return float
     */
    public function getLeadersTicks(): float
    {
        return $this->leadersTicks;
    }

    /**
     * @param float $leadersTicks
     */
    public function setLeadersTicks(float $leadersTicks): void
    {
        $this->leadersTicks = $leadersTicks;
    }


}
