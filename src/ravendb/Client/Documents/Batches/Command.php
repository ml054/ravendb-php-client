<?php

namespace RavenDB\Client\Documents\Batches;
/// WILL WRAP ALL COMMANDS FOR BATCHING UNDER "Commands" Key.
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
