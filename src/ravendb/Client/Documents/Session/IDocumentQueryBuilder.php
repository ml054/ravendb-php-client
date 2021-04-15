<?php

namespace RavenDB\Client\Documents\Session;

interface IDocumentQueryBuilder
{
    public function documentQuery(string $clazz);
}
