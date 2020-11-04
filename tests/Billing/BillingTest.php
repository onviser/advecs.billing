<?php declare(strict_types=1);

namespace Tests\Billing;

use Advecs\Billing\Billing;
use Advecs\Billing\Storage\MemoryStorage;
use PHPUnit\Framework\TestCase;

class BillingTest extends TestCase
{
    const ID1 = 1;
    const ID2 = 2;

    /** @var Billing */
    protected $hBilling;

    /** @return bool */
    public function testUserAdd(): bool
    {
        $this->assertEquals(9.42, $this->hBilling->getUserBalanceRuble(self::ID1));
        $this->assertEquals(0.31, $this->hBilling->getUserBalanceBonus(self::ID1));

        $this->assertEquals(3.15, $this->hBilling->getUserBalanceRuble(self::ID2));
        $this->assertEquals(0, $this->hBilling->getUserBalanceBonus(self::ID2));

        return true;
    }

    /** @return bool */
    public function testUserTransfer(): bool
    {
        $this->hBilling->transferUserRuble(self::ID1, self::ID2, 1.1, 'перевод средств');
        $this->assertEquals(8.32, $this->hBilling->getUserBalanceRuble(self::ID1));
        $this->assertEquals(4.25, $this->hBilling->getUserBalanceRuble(self::ID2));

        $this->hBilling->transferUserRuble(self::ID2, self::ID1, 0.03, 'перевод средств обратно');
        $this->assertEquals(8.35, $this->hBilling->getUserBalanceRuble(self::ID1));
        $this->assertEquals(4.22, $this->hBilling->getUserBalanceRuble(self::ID2));

        return true;
    }

    public function setUp(): void
    {
        $this->hBilling = (new Billing())
            ->setStorage(new MemoryStorage());

        // пользователь 1
        $this->hBilling->addUserRuble(self::ID1, 3.14, 'пополнение счета');
        $this->hBilling->addUserRuble(self::ID1, 6.28, 'еще одно пополнение счета');
        $this->hBilling->addUserBonus(self::ID1, 0.31, 'зачисление бонусов');

        // пользователь 2
        $this->hBilling->addUserRuble(self::ID2, 3.15, 'пополнение счета');
    }

    public function tearDown(): void
    {
        unset($this->hBilling);
    }
}