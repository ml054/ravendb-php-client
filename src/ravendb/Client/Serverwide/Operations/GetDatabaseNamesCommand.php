<?php

namespace RavenDB\Client\Serverwide\Operations;

use Exception;
use RavenDB\Client\Http\RavenCommand;
use RavenDB\Client\Http\ServerNode;

class GetDatabaseNamesCommand extends RavenCommand
{
    private int $_start;
    private int $_pageSize;

    public function __construct(int $_start, int $_pageSize)
    {
        $this->_start = $_start;
        $this->_pageSize = $_pageSize;
    }

    public function isReadRequest(): bool
    {
        return true;
    }

    public function createRequest(ServerNode $node): array
    {
        $url = $node->getUrl() ."/databases?start=".$this->_start."&pageSize=".$this->_pageSize."&namesOnly=true";
        return [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ];
    }

    /**
     * @param string|array $response
     * @param bool $fromCache
     * @return void
     */
    public function setResponse(string|array $response, bool $fromCache):void
    {
        if (null === $response) {
            self::throwInvalidResponse(null);
            return;
        }
        /*
         * TODO : CHECK WITH MARCIN IF UTILS IS NEEDED HERE
         * JsonNode names = mapper.readTree(response);
            if (!names.has("Databases")) {
                throwInvalidResponse();
            }
         * */
        $jsonObject = json_decode($response);
        if(!property_exists($jsonObject,'Databases')){
            self::throwInvalidResponse(null);
        }

        $databases = $jsonObject->Databases;
        if(!is_array($databases)){
            self::throwInvalidResponse();
        }

        // TODO CHECK WITH MARCIN IF THIS LOOP IS TO IMPLEMENT

        $databaseNames = [] ;
        $dbNames = $databases;
        for( $i=0; $i< count($databases); $i++){
            $databaseNames[$i] = $dbNames[$i];
        }
        $this->result = $databaseNames;

        /*
         * public void setResponse(String response, boolean fromCache) throws IOException {
            if (response == null) {
                throwInvalidResponse();
                return;
            }

            JsonNode names = mapper.readTree(response);
            if (!names.has("Databases")) {
                throwInvalidResponse();
            }

            JsonNode databases = names.get("Databases");
            if (!databases.isArray()) {
                throwInvalidResponse();
            }
            ArrayNode dbNames = (ArrayNode) databases;
            String[] databaseNames = new String[dbNames.size()];
            for (int i = 0; i < dbNames.size(); i++) {
                databaseNames[i] = dbNames.get(i).asText();
            }
            result = databaseNames;

        }
         * */
    }
}
/*
 *
    private static class GetDatabaseNamesCommand extends RavenCommand<String[]> {
        private final int _start;
        private final int _pageSize;

        public GetDatabaseNamesCommand(int _start, int _pageSize) {
            super(String[].class);
            this._start = _start;
            this._pageSize = _pageSize;
        }

        @Override
        public boolean isReadRequest() {
            return true;
        }

        @Override
        public HttpRequestBase createRequest(ServerNode node, Reference<String> url) {
            url.value = node.getUrl() + "/databases?start=" + _start + "&pageSize=" + _pageSize + "&namesOnly=true";
            return new HttpGet();
        }

        @Override

    }
}

 * */