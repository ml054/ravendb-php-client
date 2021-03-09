<?php
namespace RavenDB\Client\Http;

interface IRaftCommand
{
    public function getRaftUniqueRequestId():string;
}