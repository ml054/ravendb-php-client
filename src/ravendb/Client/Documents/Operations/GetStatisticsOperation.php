<?php

namespace RavenDB\Client\Documents\Operations;

use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Http\RavenCommand;

class GetStatisticsOperation implements IMaintenanceOperation
{
    private ?string $_debugTag;
    private ?string $_nodeTag;

    public function __construct(?string $debugTag=null, ?string $nodeTag=null)
    {
        $this->_debugTag = $debugTag;
        $this->_nodeTag = $nodeTag;
    }

    public function getCommand(DocumentConventions $conventions): RavenCommand
    {
        // TODO: Implement getCommand() method.
    }
}
/*  TODO: SOURCE CODE REFERENCE(S) ( [] NODEJS | [x] JAVA )
public class GetStatisticsOperation implements IMaintenanceOperation<DatabaseStatistics> {


    public static class GetStatisticsCommand extends RavenCommand<DatabaseStatistics> {

        private String debugTag;

        public GetStatisticsCommand() {
            super(DatabaseStatistics.class);
        }

        public GetStatisticsCommand(String debugTag, String nodeTag) {
            super(DatabaseStatistics.class);
            this.debugTag = debugTag;
            this.selectedNodeTag = nodeTag;
        }

        @Override
        public HttpRequestBase createRequest(ServerNode node, Reference<String> url) {
            url.value = node.getUrl() + "/databases/" + node.getDatabase() + "/stats";
            if (debugTag != null) {
                url.value += "?" + debugTag;
            }

            return new HttpGet();
        }

        @Override
        public void setResponse(String response, boolean fromCache) throws IOException {
            result = mapper.readValue(response, DatabaseStatistics.class);
        }

        @Override
        public boolean isReadRequest() {
            return true;
        }
    }

}
* */