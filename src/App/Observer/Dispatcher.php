<?php declare(strict_types=1);

namespace Advecs\App\Observer;

use Advecs\App\Observer\Event\EventInterface;

/**
 * Class Dispatcher
 * @package Advecs\App\Observer
 */
class Dispatcher
{
    /** @var SubscriberInterface[] */
    protected $subscriber = [];

    /**
     * @param SubscriberInterface $hSubscriber
     * @return $this
     */
    public function addSubscriber(SubscriberInterface $hSubscriber): self
    {
        foreach ($hSubscriber->getEvent() as $eventName => $method) {
            $this->subscriber[$eventName][] = [$hSubscriber, $method];
        }
        return $this;
    }

    /**
     * @param string $eventName
     * @param EventInterface $hEvent
     * @return bool
     */
    public function dispatch(string $eventName, EventInterface $hEvent): bool
    {
        if (isset($this->subscriber[$eventName])) {
            foreach ($this->subscriber[$eventName] as [$hSubscriber, $method]) {
                call_user_func([$hSubscriber, $method], $hEvent, $this);
            }
        }
        return true;
    }
}