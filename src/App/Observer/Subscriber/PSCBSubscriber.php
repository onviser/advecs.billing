<?php declare(strict_types=1);

namespace Advecs\App\Observer\Subscriber;

use Advecs\App\Debug\Debug;
use Advecs\App\Observer\Event\PSCB\PSCBPaymentEvent;
use Advecs\App\Observer\Subscriber;
use Closure;

/**
 * Class PSCBSubscriber
 * @package Advecs\App\Observer\Subscriber
 */
class PSCBSubscriber extends Subscriber
{
    /** @var Debug */
    protected $hDebug;

    /**
     * MySQLSubscriber constructor.
     * @param Debug $hDebug
     */
    public function __construct(Debug $hDebug)
    {
        $this->hDebug = $hDebug;
    }

    /** @return Closure[] */
    public function getEvent(): array
    {
        return [
            /** @see onPaymentAdd */
            PSCBPaymentEvent::EVENT_ADD     => 'onPaymentAdd',
            /** @see onPaymentReceive */
            PSCBPaymentEvent::EVENT_RECEIVE => 'onPaymentReceive',
        ];
    }

    /**
     * @param PSCBPaymentEvent $hEvent
     * @return bool
     */
    public function onPaymentAdd(PSCBPaymentEvent $hEvent): bool
    {
        $this->hDebug->add('onPaymentAdd: ' . $hEvent->getPayment()->getId());
        return true;
    }

    /**
     * @param PSCBPaymentEvent $hEvent
     * @return bool
     */
    public function onPaymentReceive(PSCBPaymentEvent $hEvent): bool
    {
        $this->hDebug->add('PSCBPaymentEvent: ' . $hEvent->getPayment()->getId());
        return true;
    }
}