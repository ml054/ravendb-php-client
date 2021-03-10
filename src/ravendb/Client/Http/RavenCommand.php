<?php

namespace RavenDB\Client\Http;

use Exception;
use http\Exception\RuntimeException;
use RavenDB\Client\Exceptions\IllegalStateException;

abstract class RavenCommand
{
    protected $resultClass;
    protected int $statusCode;
    protected $responseType;
    protected int $timeout;
    protected bool $canCache;
/*    protected bool $canCacheAggressively;*/
    protected string $selectedNodeTag;
    protected int $numberOfAttempts;
    public const CLIENT_VERSION = "5.0.0";
    protected object $result;
    public $failoverTopologyEtag = -2;

    public abstract function isReadRequest(): bool;

    public abstract function createRequest(ServerNode $node, &$url);

    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseType(): RavenCommandResponseType
    {
        return $this->responseType;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getResult(): ?object
    {
        return $this->result;
    }

    public function setResult(object|string $result): void
    {
        $this->result = $result;
    }

    public function canCache(): bool
    {
        return $this->canCache;
    }

    public function getSelectedNodeTag(): string
    {
        return $this->selectedNodeTag;
    }

    public function getNumberOfAttempts(): int
    {
        return $this->numberOfAttempts;
    }

    public function setNumberOfAttempts(int $numberOfAttempts): void
    {
        $this->numberOfAttempts = $numberOfAttempts;
    }

    protected static function throwInvalidResponse(?Exception $cause = null): void
    {
        if (null === $cause) {
            throw new IllegalStateException("Response is invalid");
        }

        throw new IllegalStateException("Response is invalid: " . $cause->getMessage());
    }
    /**
     * @throws
    */
    public function setResponse(string $response, bool $fromCache) // TODO : CONFIRM THE SCOPE OF THE UNUSED PARAMETERS response and fromCache
    {

        if ($this->responseType == RavenCommandResponseType::EMPTY || $this->responseType == RavenCommandResponseType::RAW) {
            try {
                $this->throwInvalidResponse();
            } catch (IllegalStateException $e) {
            }
        }

        throw new Exception(" command must override the setResponse method which expects response with the following type: ");
    }


    protected function RavenCommand($resultClass): void
    {
        $this->resultClass = $resultClass;
        $this->responseType = RavenCommandResponseType::OBJECT;
        $this->canCache = true;
        $this->canCacheAggressively = true;
    }

    protected function urlEncode(string $value): string
    {
        try {
            return utf8_encode($value);
        } catch (Exception $e) { // TODO : IMPLEMENT THE EXPECTED EXCEPTION : UnsupportedEncodingException
            throw new RuntimeException($e);
        }
    }
}
/*
public abstract class RavenCommand<TResult> {

    protected final Class<TResult> resultClass;
    protected TResult result;
    protected int statusCode;
    protected RavenCommandResponseType responseType;
    protected Duration timeout;
    protected boolean canCache;
    protected boolean canCacheAggressively;
    protected String selectedNodeTag;
    protected int numberOfAttempts;
    protected final ObjectMapper mapper = JsonExtensions.getDefaultMapper();

    public long failoverTopologyEtag = -2;

    public abstract boolean isReadRequest();

    public RavenCommandResponseType getResponseType() {
        return responseType;
    }

    public Duration getTimeout() {
        return timeout;
    }

    public void setTimeout(Duration timeout) {
        this.timeout = timeout;
    }

    public int getStatusCode() {
        return statusCode;
    }

    public void setStatusCode(int statusCode) {
        this.statusCode = statusCode;
    }

    public TResult getResult() {
        return result;
    }

    public void setResult(TResult result) {
        this.result = result;
    }

    public boolean canCache() {
        return canCache;
    }

    public boolean canCacheAggressively() {
        return canCacheAggressively;
    }

    public String getSelectedNodeTag() {
        return selectedNodeTag;
    }

    public int getNumberOfAttempts() {
        return numberOfAttempts;
    }

    public void setNumberOfAttempts(int numberOfAttempts) {
        this.numberOfAttempts = numberOfAttempts;
    }

    protected RavenCommand(Class<TResult> resultClass) {
        this.resultClass = resultClass;
        responseType = RavenCommandResponseType.OBJECT;
        this.canCache = true;
        this.canCacheAggressively = true;
    }

    protected RavenCommand(RavenCommand<TResult> copy) {
        this.resultClass = copy.resultClass;
        this.canCache = copy.canCache;
        this.canCacheAggressively = copy.canCacheAggressively;
        this.selectedNodeTag = copy.selectedNodeTag;
        this.responseType = copy.responseType;
    }

    public abstract HttpRequestBase createRequest(ServerNode node, Reference<String> url);

    public void setResponse(String response, boolean fromCache) throws IOException {
        if (responseType == RavenCommandResponseType.EMPTY || responseType == RavenCommandResponseType.RAW) {
            throwInvalidResponse();
        }

        throw new UnsupportedOperationException(responseType.name() + " command must override the setResponse method which expects response with the following type: " + responseType);
    }

    public CloseableHttpResponse send(CloseableHttpClient client, HttpRequestBase request) throws IOException {
        return client.execute(request);
    }

    @SuppressWarnings("unused")
    public void setResponseRaw(CloseableHttpResponse response, InputStream stream) {
        throw new UnsupportedOperationException("When " + responseType + " is set to Raw then please override this method to handle the response. ");
    }

    private Map<ServerNode, Exception> failedNodes;

    public Map<ServerNode, Exception> getFailedNodes() {
        return failedNodes;
    }

    public void setFailedNodes(Map<ServerNode, Exception> failedNodes) {
        this.failedNodes = failedNodes;
    }

    @SuppressWarnings("unused")
    protected String urlEncode(String value) {
        try {
            return URLEncoder.encode(value, "UTF-8");
        } catch (UnsupportedEncodingException e) {
            throw new RuntimeException(e);
        }
    }

    public static void ensureIsNotNullOrString(String value, String name) {
        if (StringUtils.isEmpty(value)) {
            throw new IllegalArgumentException(name + " cannot be null or empty");
        }
    }

    public boolean isFailedWithNode(ServerNode node) {
        return failedNodes != null && failedNodes.containsKey(node);
    }

    public ResponseDisposeHandling processResponse(HttpCache cache, CloseableHttpResponse response, String url) {
        HttpEntity entity = response.getEntity();

        if (entity == null) {
            return ResponseDisposeHandling.AUTOMATIC;
        }

        if (responseType == RavenCommandResponseType.EMPTY || response.getStatusLine().getStatusCode() == HttpStatus.SC_NO_CONTENT) {
            return ResponseDisposeHandling.AUTOMATIC;
        }

        try {
            if (responseType == RavenCommandResponseType.OBJECT) {
                Long contentLength = entity.getContentLength();
                if (contentLength == 0) {
                    HttpClientUtils.closeQuietly(response);
                    return ResponseDisposeHandling.AUTOMATIC;
                }

                // we intentionally don't dispose the reader here, we'll be using it
                // in the command, any associated memory will be released on context reset
                String json = IOUtils.toString(entity.getContent(), StandardCharsets.UTF_8);
                if (cache != null) //precaution
                {
                    cacheResponse(cache, url, response, json);
                }
                setResponse(json, false);
                return ResponseDisposeHandling.AUTOMATIC;
            } else {
                setResponseRaw(response, entity.getContent());
            }
        } catch (IOException e) {
            throw new RuntimeException(e);
        } finally {
            HttpClientUtils.closeQuietly(response);
        }

        return ResponseDisposeHandling.AUTOMATIC;
    }

    protected void cacheResponse(HttpCache cache, String url, CloseableHttpResponse response, String responseJson) {
        if (!canCache()) {
            return;
        }

        String changeVector = HttpExtensions.getEtagHeader(response);
        if (changeVector == null) {
            return;
        }

        cache.set(url, changeVector, responseJson);
    }

    protected static void throwInvalidResponse() {
        throw new IllegalStateException("Response is invalid");
    }

    protected static void throwInvalidResponse(Exception cause) {
        throw new IllegalStateException("Response is invalid: " + cause.getMessage(), cause);
    }

    @SuppressWarnings("unused")
    protected void addChangeVectorIfNotNull(String changeVector, HttpRequestBase request) {
        if (changeVector != null) {
            request.addHeader("If-Match", "\"" + changeVector + "\"");
        }
    }

    @SuppressWarnings({"unused", "EmptyMethod"})
    public void onResponseFailure(CloseableHttpResponse response) {

    }
}

 * */