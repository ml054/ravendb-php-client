<?php

namespace RavenDB\Client\Documents\Operations;

use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;

class GetStatisticsCommand extends RavenCommand
{

    public function isReadRequest(): bool
    {
        // TODO: Implement isReadRequest() method.
    }

    public function createRequest(ServerNode $node): array
    {
        // TODO: Implement createRequest() method.
    }
}
/*  TODO: SOURCE CODE REFERENCE(S) ( [] NODEJS | [x] JAVA )
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
* */