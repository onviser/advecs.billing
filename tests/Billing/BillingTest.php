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

        // пользователь 1
        $id = 1;
        $hBilling->addUserRuble($id, 3.14, 'пополнение счета');
        $hBilling->addUserRuble($id, 6.28, 'еще одно пополнение счета');
        $hBilling->addUserBonus($id, 0.31, 'зачисление бонусов');
        $this->assertEquals(9.42, $hBilling->getUserBalanceRuble($id));
        $this->assertEquals(0.31, $hBilling->getUserBalanceBonus($id));

        // пользователь 2
        $id = 2;
        $hBilling->addUserRuble($id, 3.15, 'пополнение счета');
        $this->assertEquals(3.15, $hBilling->getUserBalanceRuble($id));
        $this->assertEquals(0, $hBilling->getUserBalanceBonus($id));

        return true;
    }
}