<?php

namespace Riverwaysoft\ApiTools\DomainEvents\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Riverwaysoft\ApiTools\DomainEvents\EventSourceCollector;
use Symfony\Component\Messenger\MessageBusInterface;

class DoctrineDomainEventsCollector
{
    /** @var EventSourceCollector[] */
    protected array $entities = [];

    public function __construct(private MessageBusInterface $messageBus)
    {
        $this->isActive = true;
    }

    private $isActive;

    public function deactivate()
    {
        $this->isActive = false;
    }

    public function activate()
    {
        $this->isActive = true;
    }
    public function postPersist(LifecycleEventArgs $event)
    {
        $this->keepProvider($event);
    }

    private function keepProvider(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if (!$this->isActive || !$entity instanceof EventSourceCollector || in_array($entity, $this->entities, true)) {
            return;
        }

        $this->entities[] = $entity;
    }

    public function postLoad(LifecycleEventArgs $event)
    {
        $this->keepProvider($event);
    }

    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->keepProvider($event);
    }


    public function postFlush(PostFlushEventArgs $event)
    {
        foreach ($this->entities as $entity) {
            foreach ($entity->popMessages() as $arg) {
                $this->messageBus->dispatch($arg[0], $arg[1]);
            }
        }
    }
}
