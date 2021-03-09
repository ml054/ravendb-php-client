<?php

namespace RavenDB\Client\Http;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class HttpClientCommand
 * @package RavenDB\Client\Http
 */
class HttpClient
{
    private $curlClient;

    public function __construct()
    {
        $this->curlClient = new CurlHttpClient();
    }

    public function curlRequest(string $url, string $method = "GET"): string
    {
        return $this->curl()->request($method, $url)->getContent();
    }

    public function curl(): CurlHttpClient
    {
        return $this->curlClient;
    }

    public function curlUrl(string $url, string $method = "GET"): ResponseInterface
    {
        try {
            return $this->curl()->request($url, $method);
        } catch (TransportExceptionInterface $e) {
        }
    }
}
