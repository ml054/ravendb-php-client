<?php

namespace RavenDB\Client\Documents\Session;

interface IDocumentQueryBuilder
{
    /**
     * Query the specified index
     * @param string $clazz
     * @param string|null $indexName
     * @param string|null $collectionName
     * @param bool|null $isMapReduce
     * @return \RavenDB\Client\Documents\Session\IDocumentQuery
     */
    public function documentQuery(string $clazz, ?string $indexName=null, ?string $collectionName=null,?bool $isMapReduce=null):IDocumentQuery;
}
