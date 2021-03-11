<?php


namespace RavenDB\Client\Serverwide\Commands;


use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;

class GetClusterTopologyCommand extends RavenCommand
{
    public function isReadRequest(): bool
    {
        // TODO: Implement isReadRequest() method.
    }

    public function createRequest(ServerNode $node, &$url)
    {
        // TODO: Implement createRequest() method.
    }
}
/*
 * public class GetClusterTopologyCommand extends RavenCommand<ClusterTopologyResponse> {

    private final String _debugTag;

    public GetClusterTopologyCommand() {
        super(ClusterTopologyResponse.class);

        _debugTag = null;
    }

    public GetClusterTopologyCommand(String debugTag) {
        super(ClusterTopologyResponse.class);
        _debugTag = debugTag;
    }

    @Override
    public HttpRequestBase createRequest(ServerNode node, Reference<String> url) {
        url.value = node.getUrl() + "/cluster/topology";

        if (_debugTag != null)
            url.value += "?" + _debugTag;

        return new HttpGet();
    }

    @Override
    public void setResponse(String response, boolean fromCache) throws IOException {
        if (response == null) {
            throwInvalidResponse();
        }

        result = mapper.readValue(response, resultClass);
    }

    @Override
    public boolean isReadRequest() {
        return true;
    }
}
 * */