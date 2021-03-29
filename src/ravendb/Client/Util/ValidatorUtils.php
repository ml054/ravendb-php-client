<?php

namespace RavenDB\Client\Util;

use Exception;
/**
 * Class ValidatorUtils
 * @package RavenDB\Client\Util
 */
class ValidatorUtils
{
    /**
     * Validate URL from array context
     * @param array|string $initialUrls
     * @param string|null $certificate
     * @return array
     * @throws Exception
     */
    public static function validateUrl(array|string $initialUrls, string $certificate = null): array
    {
        if(is_string($initialUrls)){
            throw new Exception('URL as as string is not yet supported');
        }

        $requireHttps = null !== $certificate ?? false;
        $cleanUrl = [];
        foreach($initialUrls as $url){
            if(!filter_var($url, FILTER_VALIDATE_URL)){
                throw new Exception($url." is not a valid url");
            }
            $cleanUrl[] = StringUtils::stripEnd($url,'/');
        }
        if(!$requireHttps){
            return $cleanUrl;
        }

        foreach($initialUrls as $url){
            if(!StringUtils::startWith($url,"http://")){
                continue;
            }
            if(null !== $certificate){
                throw new Exception("The url " . $url . " is using HTTP, but a certificate is specified, which require us to use HTTPS");
            }
            throw new Exception("The url "  . $url .  " is using HTTP, but other urls are using HTTPS, and mixing of HTTP and HTTPS is not allowed.");
        }
        return $cleanUrl;
    }
}
