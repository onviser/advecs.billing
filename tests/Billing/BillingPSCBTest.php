<?php declare(strict_types=1);

namespace Tests\Billing;

use Advecs\Billing\Billing;
use Advecs\Billing\PSCB\PSCBNotify;
use Advecs\Billing\PSCB\PSCBPayment;
use Advecs\Billing\Storage\MemoryStorage;
use PHPUnit\Framework\TestCase;

class BillingPSCBTest extends TestCase
{
    /** @return bool */
    public function testAddPSCBPayment(): bool
    {
        $hBilling = $this->getBilling();

        $hPSCBPayment = (new PSCBPayment(1, 1.1))
            ->setType(PSCBPayment::TYPE_CARD)
            ->setStatus(PSCBPayment::STATUS_NEW)
            ->setComment('тестовый комментарий');
        $result = $hBilling->addPSCBPayment($hPSCBPayment);
        $this->assertTrue($result);

        $hPSCBNotify = (new PSCBNotify('raw', 'json'));
        $result = $hBilling->addPSCBNotify($hPSCBNotify);
        $this->assertTrue($result);

        return true;
    }

    /** @return Billing */
    public function getBilling(): Billing
    {
        return (new Billing())
            ->setStorage(new MemoryStorage());
    }
}