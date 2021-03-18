<?php

namespace RavenDB\Client\Http;

use DateTime;
// TODO : IF NON NULL TOPOLOGY NODESTATUS SHOULD BE IMPLEMENTED
class NodeStatus
{
    private bool $connected;
    private string $errorDetails;
    private DateTime $lastSend; // TODO : DateTimeInterface
    private DateTime $lastReply; // TODO : DateTimeInterface
    private string $lastSentMessage;
    private string $lastMatchingIndex;

    public function isConnected(): bool
    {
        return $this->connected;
    }

    public function setConnected(bool $connected): void
    {
        $this->connected = $connected;
    }

    public function getErrorDetails(): string
    {
        return $this->errorDetails;
    }

    public function setErrorDetails(string $errorDetails): void
    {
        $this->errorDetails = $errorDetails;
    }

    public function getLastSend(): DateTime
    {
        return $this->lastSend;
    }

    public function setLastSend(DateTime $lastSend): void
    {
        $this->lastSend = $lastSend;
    }

    public function getLastReply(): DateTime
    {
        return $this->lastReply;
    }

    public function setLastReply(DateTime $lastReply): void
    {
        $this->lastReply = $lastReply;
    }

    public function getLastSentMessage(): string
    {
        return $this->lastSentMessage;
    }

    public function setLastSentMessage(string $lastSentMessage): void
    {
        $this->lastSentMessage = $lastSentMessage;
    }

    public function getLastMatchingIndex(): string
    {
        return $this->lastMatchingIndex;
    }

    public function setLastMatchingIndex(string $lastMatchingIndex): void
    {
        $this->lastMatchingIndex = $lastMatchingIndex;
    }
}
/* PHP-MIGRATION-STATUS : EMPTY SOURCE CLASS BODY - MEANS ALL RESOURCES ARE IMPORTED. TO CLEAN ONCE VALIDATED
public class NodeStatus {

}
 * */