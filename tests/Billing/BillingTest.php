<?php declare(strict_types=1);

namespace Tests\Billing;

use Advecs\Billing\Billing;
use Advecs\Billing\Storage\MemoryStorage;
use PHPUnit\Framework\TestCase;

class BillingTest extends TestCase
{
    /** @return bool */
    public function testAddUser(): bool
    {
        $hBilling = (new Billing())
            ->setStorage(new MemoryStorage());

        $hBilling->addUserRuble(1, 3.14, 'пополнение счета');
        $hBilling->addUserRuble(1, 6.28, 'еще одно пополнение счета');
        $hBilling->addUserBonus(1, 0.31, 'зачисление бонусов');

        $this->assertEquals(9.42, $hBilling->getUserBalanceRuble(1));
        $this->assertEquals(0.31, $hBilling->getUserBalanceBonus(1));

        return true;
    }
}