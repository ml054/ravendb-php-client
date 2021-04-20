<?php

namespace RavenDB\Tests\Client\BatchCommand;

class Command
{
    private array $Commands;

    public function getCommands(): array
    {
        return $this->Commands;
    }

    public function setCommands(array $Commands): self
    {
        $this->Commands = $Commands;
        return $this;
    }

}
