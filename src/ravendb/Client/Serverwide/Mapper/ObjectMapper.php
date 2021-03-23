<?php

namespace RavenDB\Client\Serverwide\Mapper;

use Exception;
use RavenDB\Client\Http\ClusterTopology;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ObjectMapper
{
    /**
     * @param $response
     * @return ClusterTopology
     * @throws ExceptionInterface
     * @throws Exception
     */
   public function topology($response):ClusterTopology{

       $maxDepthHandler = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {};
       $defaultContext = [
           AbstractObjectNormalizer::MAX_DEPTH_HANDLER => $maxDepthHandler,
       ];
       $data = json_decode($response);
       $normalizer = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), null, null, null, null, $defaultContext);
       $serializer = new Serializer([$normalizer]);
       $result = $serializer->normalize($data, null, [ AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true ]);

       if(!array_key_exists('topology',$result)){
           throw new Exception('Wrong topology response type');
       }
       $topology = new ClusterTopology();
       $topology->mapOptions($result);
       return $topology;
   }
}
