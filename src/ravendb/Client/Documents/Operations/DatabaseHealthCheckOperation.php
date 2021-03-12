<?php

namespace RavenDB\Client\Documents\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;

class DatabaseHealthCheckOperation implements IMaintenanceOperation
{
    public function getCommand(DocumentConventions $conventions): RavenCommand
    {
        // TODO: Implement getCommand() method.
        //
    }
}
/* TODO: DatabaseHealthCheckOperation
 * public class DatabaseHealthCheckOperation implements IMaintenanceOperation {

    @Override
    public RavenCommand getCommand(DocumentConventions conventions) {
        return new DatabaseHealthCheckCommand();
    }

    private static class DatabaseHealthCheckCommand extends VoidRavenCommand {
        @Override
        public HttpRequestBase createRequest(ServerNode node, Reference<String> url) {
            url.value = node.getUrl() + "/databases/" + node.getDatabase() + "/healthcheck";

            return new HttpGet();
        }
    }
}

 * */