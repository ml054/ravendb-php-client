<?php

namespace RavenDB\Client\Http;

use Exception;
use http\Env\Response;
use http\Exception\RuntimeException;
use HttpResponse;
use InvalidArgumentException;
use RavenDB\Client\Exceptions\IllegalStateException;
use RavenDB\Client\Util\StringUtils;
use CurlHandle;
abstract class RavenCommand
{
    protected object $resultClass;
    protected int $statusCode;
    protected ?int $timeout=null;
    protected bool $canCache;
    /*    protected bool $canCacheAggressively;*/
    protected string $selectedNodeTag;
    protected int $numberOfAttempts;
    public const CLIENT_VERSION = "5.0.0";
    protected null|string|array|object $result=null;
    public int $failoverTopologyEtag = -2;
    protected RavenCommandResponseType|string $responseType;
    private ServerNode|array $failedNodes;

    public abstract function isReadRequest(): bool;

    public abstract function createRequest(ServerNode $node): array;

    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    /**
     * TODO REQUESTED IMPLEMENT THE RESPONSE TYPE
    */

    public function getResponseType(): RavenCommandResponseType
    {
        return $this->responseType;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getResult(): array|string|null|object
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
    public function setResponse(string|array $response, bool $fromCache) // TODO : CONFIRM THE SCOPE OF THE UNUSED PARAMETERS response and fromCache
    {

        if ($this->responseType === RavenCommandResponseType::EMPTY || $this->responseType === RavenCommandResponseType::RAW) {
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
        // $this->canCache = true; TODO : not yet in php scope
        // $canCacheAggressively = true;
    }

    protected function urlEncode(string $value): string
    {
        try {
            return utf8_encode($value);
        } catch (Exception $e) { // TODO : IMPLEMENT THE EXPECTED EXCEPTION : UnsupportedEncodingException
            throw new RuntimeException($e);
        }
    }

    public static function ensureIsNotNullOrString(string $value, string $name):void {
        if (StringUtils::isNotBlank($value)) {
            throw new InvalidArgumentException($name ." cannot be null or empty");
        }
    }

    /**
     * @param string $response
     * @param string|object $stream
     * @throws Exception
     * @SuppressWarnings("unused")  /* TODO : CHECK WITH MARCIN
     */
    public function setResponseRaw(string $response, string|object $stream): void {
        throw new Exception("When " .$this->responseType. " is set to Raw then please override this method to handle the response. ");
    }
    public function getFailedNodes():ServerNode {
        return $this->failedNodes;
    }

    public function setFailedNodes(array $failedNodes): void {
        $this->failedNodes = $failedNodes;
    }

    public function isFailedWithNode(ServerNode $node):bool {
        return $this->failedNodes !== null && array_key_exists($node,$this->failedNodes); // TODO : failedNodes.containsKey()
    }

    public function processResponse(string $cache, string $response, string $url): string
    {
        $entity = $response->getEntity(); // TODO CHECK IF CloseableHttpResponse IS TO IMPLEMENT

        if(null === $entity){
            return ResponseDisposeHandling::AUTOMATIC;
        }

        if ($this->responseType === RavenCommandResponseType::EMPTY || $response->getStatusLine()->getStatusCode() === HttpStatus::SC_NO_CONTENT) { // TODO
            return ResponseDisposeHandling::AUTOMATIC;
        }

        try {
            if ($this->responseType == RavenCommandResponseType::OBJECT) {
                $contentLength = $entity->getContentLength();
                if ($contentLength === 0) {
                   // HttpClientUtils.closeQuietly(response); // TODO CHECK WITH MARCIN IF HERE CURL SHOULD CLOSE THE SESSION
                    return ResponseDisposeHandling::AUTOMATIC;
                }

                // we intentionally don't dispose the reader here, we'll be using it
                // in the command, any associated memory will be released on context reset
                //String json = IOUtils.toString(entity.getContent(), StandardCharsets.UTF_8);
               /* if (cache !== null) //precaution
                {
                    cacheResponse(cache, url, response, json);
                }
                $this->setResponse($json, false);*/
                return ResponseDisposeHandling::AUTOMATIC;
            } else {
                $this->setResponseRaw($response, $entity->getContent());
            }
        } catch (Exception $e) {
        throw new RuntimeException($e);
  //  } finally {
       // HttpClientUtils.closeQuietly(response); // TODO
    }
        return ResponseDisposeHandling::AUTOMATIC;
    }
}
/*


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