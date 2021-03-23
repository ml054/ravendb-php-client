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

       // TODO REMOVE MAX
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
       // TODO IMPLEMENT THE CLUSTER TOPOLOGY RESPONSE OBJECT AND NOT ClusterTopology (compliance)
       $topology = new ClusterTopology();
       $topology->mapOptions($result);
       return $topology;
   }

    /**
     * @param string $response
     * @param object $targetClass
     * @return mixed
     * @throws Exception
     */
   public function genericMapper(string $response, object $targetClass):object{

       if(!is_object($targetClass)){
            throw new Exception('Please provide a target class');
        }

       if(null === $response){
            throw new Exception('Please provide a valid response');
        }
       // TODO REMOVE MAX
       $maxDepthHandler = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {};
       $defaultContext = [
           AbstractObjectNormalizer::MAX_DEPTH_HANDLER => $maxDepthHandler,
       ];
       $data = json_decode($response);
       $normalizer = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), null, null, null, null, $defaultContext);
       $serializer = new Serializer([$normalizer]);
       $result = $serializer->normalize($data, null, [ AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true ]);

       // TODO IMPLEMENT THE CLUSTER TOPOLOGY RESPONSE OBJECT AND NOT ClusterTopology (compliance)
       $target = new $targetClass();
       if(!method_exists($targetClass,'mapOptions')){
           throw new Exception('You need to implement the mapOptions method on the target class');
       }
       $target->mapOptions($result);
       return $target;
   }

   public function mapSource(object $source, object $destination){
      //  dd($destination);
   }
}
