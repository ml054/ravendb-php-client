<?php

namespace RavenDB\Client\Http;

class NodeSelectorState
{
        public Topology $topology;
        public ServerNode $nodes;
        public int $failures;
        public int $fastestRecords;
        public int $fastest;
        // public final AtomicInteger speedTestMode = new AtomicInteger(0);
        public function __construct(Topology $topology)
        {
            $this->topology = $topology;
            $this->nodes = $topology->getNodes();
            $this->failures = count($topology->getNodes());
            for($i=0;$i<$this->failures;$i++){
                //$this->failures[$i] =
                // this.failures[i] = new AtomicInteger(0);
            }
            $this->fastestRecords = count($topology->getNodes());
        }
}
/*  TODO: SOURCE CODE REFERENCE(S) ( [] NODEJS | [] JAVA )
    private static class NodeSelectorState {
        public NodeSelectorState(Topology topology) {
            this.failures = new AtomicInteger[topology.getNodes().size()];
        }
    }
* */