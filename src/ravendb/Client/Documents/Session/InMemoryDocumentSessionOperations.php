<?php


namespace RavenDB\Client\Documents\Session;


use RavenDB\Client\Documents\Operations\OperationExecutor;
use RavenDB\Client\Http\RequestExecutor;
use RavenDB\Client\Primitives\Closable;
// TODO: CHECK WITH MARCIN IF THIS CLASS IS TO IMPORT
abstract class InMemoryDocumentSessionOperations implements Closable
{
    protected RequestExecutor $_requestExecutor;
    private OperationExecutor $_operationExecutor;
}