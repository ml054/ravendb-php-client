<?php

namespace RavenDB\Client\Documents\Session;

interface IAdvancedSessionOperations extends IAdvancedDocumentSessionOperations
{
    public function exists():bool ;

}
