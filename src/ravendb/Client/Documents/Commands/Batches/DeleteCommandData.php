<?php

namespace RavenDB\Client\Documents\Commands\Batches;

use Doctrine\Common\Collections\ArrayCollection;
use RavenDB\Client\Constants;
use RavenDB\Client\Documents\Batches\ICommandData;
use RavenDB\Client\Documents\Conventions\DocumentConventions;
use RavenDB\Client\Extensions\JsonExtensions;

class DeleteCommandData implements ICommandData
{

    private string $id;
    private string $name;
    private string $changeVector;

    public function __construct(string $id, string $changeVector)
    {
        $this->id = $id;
        if(null === $id) throw new \InvalidArgumentException('Id cannot be null');
        $this->changeVector = $changeVector;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getChangeVector(): string
    {
        return $this->changeVector;
    }

    public function serialize(DocumentConventions $conventions):void {
        $writeObject = new ArrayCollection();
        $writeObject->set("id",$this->id);
        $writeObject->set("ChangeVector",$this->changeVector);
        $writeObject->set("Type",Constants::CURLOPT_CUSTOMREQUEST_DELETE);
        $serializer = JsonExtensions::storeSerializer();
        $serializer->serialize($writeObject,'json');
    }
}
