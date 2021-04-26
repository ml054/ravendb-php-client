<?php

namespace RavenDB\Client\Documents\Commands\Batches;

class BatchOptions
{
    private ?IndexBatchOptions $indexOptions=null;

    public function getIndexOptions(): ?IndexBatchOptions
    {
        return $this->indexOptions;
    }
    public function setIndexOptions(IndexBatchOptions $indexOptions): void
    {
        $this->indexOptions = $indexOptions;
    }

}
