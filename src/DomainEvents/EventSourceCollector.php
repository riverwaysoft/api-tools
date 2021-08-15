<?php


namespace Riverwaysoft\ApiTools\DomainEvents;


use Symfony\Component\Messenger\Stamp\StampInterface;

abstract class  EventSourceCollector
{
    protected array $messages = [];

    public function popMessages(): array
    {
        $messages = $this->messages;

        $this->messages = [];

        return $messages;
    }

    /**
     * @param object $message
     * @param StampInterface[] $stamps
     */
    public function rememberMessage(object $message, array $stamps=[])
    {
        $this->messages[] = [$message, $stamps];
    }
}