<?php

namespace RavenDB\Client\Serverwide\Commands;

use HttpResponseException;
use RavenDB\Client\Http\ClusterTopologyResponse;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;
use RavenDB\Client\Util\GetClusterTopologyAttributesTransformer;

class GetClusterTopologyCommand extends RavenCommand
{
    private null|string $_debugTag;
    // TODO CHECK WITH MARCIN FOR IMPLEMENTATION OF THE TRANSFORMER
    public function __construct(?string $debugTag = null)
    {
        //TODO super(ClusterTopologyResponse.class);
        $this->_debugTag = $debugTag === null ? null : $debugTag;
    }

    public function createRequest(ServerNode $node): array
    {
        $url = $node->getUrl() . "/cluster/topology";
        if ($this->_debugTag !== null) $url .= "?" . $this->_debugTag;
        return [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ];
    }
/* TODO Model from Nodejs : Implement the transformer
 * public async setResponseAsync(bodyStream: stream.Stream, fromCache: boolean): Promise<string> {
        if (!bodyStream) {
            this._throwInvalidResponse();
        }

        let body: string = null;
        const result = await this._pipeline<ClusterTopologyResponse>()
            .collectBody(b => body = b)
            .parseJsonSync()
            .objectKeysTransform({
                defaultTransform: "camel",
                ignorePaths: [/topology\.(members|promotables|watchers|allNodes)\./i]
            })
            .process(bodyStream);

        const clusterTpl = Object.assign(new ClusterTopology(), result.topology);
        this.result = Object.assign(result as ClusterTopologyResponse, { topology: clusterTpl });
        this.result.status = new Map(Object.entries(this.result.status));
        return body;
    }

 * */
    public function setResponse(string|array $response, bool $fromCache)
    {
        // TODO : THROWING A REGULAR EXCEPTION
        if (null === $response) {
            throw new HttpResponseException();
        }
        // TODO: ORIGINAL result = mapper.readValue(response, resultClass);
        $this->result = $response;
    }

    public function isReadRequest(): bool
    {
        return true;
    }
}
/*TODO SOURCE
public class GetClusterTopologyCommand extends RavenCommand<ClusterTopologyResponse> {

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

*/