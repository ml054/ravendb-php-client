<?php


namespace RavenDB\Client\Documents\Session;


class ResponseTimeInformation
{
    private int $totalServerDuration;
    private int $totalClientDuration;

    private ResponseTimeItem $durationBreakdown;

    public function ResponseTimeInformation() {
       /* TODO: check with Marcin how to implement the below
       totalServerDuration = Duration.ZERO;
        totalClientDuration = Duration.ZERO;
        durationBreakdown = new ArrayList<>();*/
    }

}