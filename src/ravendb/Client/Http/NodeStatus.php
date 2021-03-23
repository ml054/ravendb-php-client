<?php

namespace RavenDB\Client\Http;

use DateTime;
// TODO : IF NON NULL TOPOLOGY NODESTATUS SHOULD BE IMPLEMENTED
class NodeStatus
{
    private bool $connected;
    private string $error_details; // TODO : SNAKE
    private DateTime $last_send; // TODO : DateTimeInterface
    private DateTime $last_reply; // TODO : DateTimeInterface SNAKE
    private string $last_sent_message;
    private string $last_matching_index; // TODO LONG IN JAVA

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
        return $this->error_details;
    }

    public function setErrorDetails(string $error_details): void
    {
        $this->error_details = $error_details;
    }

    public function getLastSend(): DateTime
    {
        return $this->last_send;
    }

    public function setLastSend(DateTime $last_send): void
    {
        $this->last_send = $last_send;
    }

    public function getLastReply(): DateTime
    {
        return $this->last_reply;
    }

    public function setLastReply(DateTime $last_reply): void
    {
        $this->last_reply = $last_reply;
    }

    public function getLastSentMessage(): string
    {
        return $this->last_sent_message;
    }

    public function setLastSentMessage(string $last_sent_message): void
    {
        $this->last_sent_message = $last_sent_message;
    }

    public function getLastMatchingIndex(): string
    {
        return $this->last_matching_index;
    }

    public function setLastMatchingIndex(string $last_matching_index): void
    {
        $this->last_matching_index = $last_matching_index;
    }
}
/* PHP-MIGRATION-STATUS : EMPTY SOURCE CLASS BODY - MEANS ALL RESOURCES ARE IMPORTED. TO CLEAN ONCE VALIDATED
public class NodeStatus {

}
 * */