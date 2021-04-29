<?php

namespace RavenDB\Client\Documents\Session;

class DeletedEntitiesEnumeratorResult
{
    public object $entity;
    private bool $executeOnBeforeDelete;

    private function __construct(object $entity,bool $executeOnBeforeDelete)
    {
        $this->entity = $entity;
        $this->executeOnBeforeDelete = $executeOnBeforeDelete;
    }
    public function getEntity(): object
    {
        return $this->entity;
    }
    public function isExecuteOnBeforeDelete(): bool
    {
        return $this->executeOnBeforeDelete;
    }
}
