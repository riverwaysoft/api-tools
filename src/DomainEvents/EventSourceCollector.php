<?php

namespace Riverwaysoft\ApiTools\DomainEvents;

use Symfony\Component\Messenger\Stamp\StampInterface;

abstract class EventSourceCollector
{
    protected array $_messages = [];

    public function popMessages(): array
    {
        $messages = $this->_messages;

        $this->_messages = [];

        return $messages;
    }

    /**
     * @param object $message
     * @param StampInterface[] $stamps
     */
    public function rememberMessage(object $message, array $stamps=[])
    {
        $this->_messages[] = [$message, $stamps];
    }
}
