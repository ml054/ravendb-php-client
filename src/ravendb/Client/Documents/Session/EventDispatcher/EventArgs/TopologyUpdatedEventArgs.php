<?php

namespace RavenDB\Client\Documents\Session\EventDispatcher\EventArgs;
use RavenDB\Client\Http\Topology;

class TopologyUpdatedEventArgs
{
    private Topology $_topology;

    public function __construct(Topology $topology)
    {
        $this->_topology = $topology;
    }

    public function getTopology(): Topology
    {
        return $this->_topology;
    }

}
