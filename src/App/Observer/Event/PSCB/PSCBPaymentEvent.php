<?php declare(strict_types=1);

namespace Advecs\App\Observer\Event\PSCB;

use Advecs\App\Observer\Event\EventInterface;
use Advecs\Billing\PSCB\PSCBPayment;

/**
 * Class PSCBPaymentEvent
 * @package Advecs\App\Observer\Event\PSCB
 */
class PSCBPaymentEvent implements EventInterface
{
    const EVENT_ADD = 'pscb.add';
    const EVENT_RECEIVE = 'pscb.receive';

    /** @var PSCBPayment */
    protected $hPSCBPayment;

    /**
     * PSCBPaymentEvent constructor.
     * @param PSCBPayment $hPSCBPayment
     */
    public function __construct(PSCBPayment $hPSCBPayment)
    {
        $this->hPSCBPayment = $hPSCBPayment;
    }

    /** @return PSCBPayment */
    public function getPayment(): PSCBPayment
    {
        return $this->hPSCBPayment;
    }
}