<?php


namespace RavenDB\Client\Documents\Operations;

use RavenDB\Client\Documents\DocumentStore;

class ServerOperationExecutor
{
    private String $_nodeTag;
    private DocumentStore $_store;
    private ClusterRequestExecutor $_requestExecutor;
    private ClusterRequestExecutor $_initialRequestExecutor;

}
