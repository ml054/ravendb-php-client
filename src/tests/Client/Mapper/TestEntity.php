<?php

namespace RavenDB\Tests\Client\Mapper;

use PHPUnit\Framework\TestCase;
use RavenDB\Client\Serverwide\Mapper\Format\Casing;
use RavenDB\Client\Serverwide\Mapper\CaseStudy\Person;
use RavenDB\Client\Serverwide\Mapper\CaseStudy\PersonRole;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
/**
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/cookbook/entities-in-session.html
*/
class TestEntity extends TestCase
{
    public function testEntity(){
        $encoder = new JsonEncoder();

        $dateCallback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            return $innerObject instanceof \DateTime ? $innerObject->format(\DateTime::ISO8601) : '';
        };

        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'createdAt' => $dateCallback,
            ],
        ];

        $normalizer = new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);

        $person = new Person();
        $person->setName('cordoval');
        $person->setAge(34);
        $person->setCreatedAt(new \DateTime('now'));

        $role = new PersonRole();
        $role->setRoleName("Admin");
        $person->setRole($role);

        $jsonSerialize = $serializer->serialize($person, 'json');
        dd($jsonSerialize);
    }
}
