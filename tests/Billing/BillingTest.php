<?php declare(strict_types=1);

namespace Tests\Billing;

use Advecs\Billing\Billing;
use Advecs\Billing\Storage\MemoryStorage;
use PHPUnit\Framework\TestCase;

class BillingTest extends TestCase
{
    const ID_USER_1 = 1;
    const ID_USER_2 = 2;
    const ID_FIRM_1 = 10;
    const ID_FIRM_2 = 20;

    /** @var Billing */
    protected $hBilling;

    /** @return bool */
    public function testUserAdd(): bool
    {
        $this->assertEquals(9.42, $this->hBilling->getUserBalanceRuble(self::ID_USER_1));
        $this->assertEquals(0.31, $this->hBilling->getUserBalanceBonus(self::ID_USER_1));

        $this->assertEquals(3.15, $this->hBilling->getUserBalanceRuble(self::ID_USER_2));
        $this->assertEquals(0, $this->hBilling->getUserBalanceBonus(self::ID_USER_2));

        return true;
    }

    /** @return bool */
    public function testUserTransfer(): bool
    {
        $this->hBilling->transferUserRuble(self::ID_USER_1, self::ID_USER_2, 1.1, 'перевод средств');
        $this->assertEquals(8.32, $this->hBilling->getUserBalanceRuble(self::ID_USER_1));
        $this->assertEquals(4.25, $this->hBilling->getUserBalanceRuble(self::ID_USER_2));

        $this->hBilling->transferUserRuble(self::ID_USER_2, self::ID_USER_1, 0.03, 'перевод средств обратно');
        $this->assertEquals(8.35, $this->hBilling->getUserBalanceRuble(self::ID_USER_1));
        $this->assertEquals(4.22, $this->hBilling->getUserBalanceRuble(self::ID_USER_2));

        return true;
    }

    /** @return bool */
    public function testFirmAdd(): bool
    {
        $this->assertEquals(0, $this->hBilling->getFirmBalanceRuble(self::ID_FIRM_1));
        $this->hBilling->addFirmRuble(self::ID_FIRM_1, 7.77);
        $this->assertEquals(7.77, $this->hBilling->getFirmBalanceRuble(self::ID_FIRM_1));
        $this->hBilling->addFirmRuble(self::ID_FIRM_1, 2.23);
        $this->assertEquals(10, $this->hBilling->getFirmBalanceRuble(self::ID_FIRM_1));

        $this->assertEquals(0, $this->hBilling->getFirmBalanceRuble(self::ID_FIRM_2));
        $this->hBilling->addFirmRuble(self::ID_FIRM_2, 10000);
        $this->assertEquals(10000, $this->hBilling->getFirmBalanceRuble(self::ID_FIRM_2));

        return true;
    }

    public function setUp(): void
    {
        $this->hBilling = (new Billing())
            ->setStorage(new MemoryStorage());

        // пользователь 1
        $this->hBilling->addUserRuble(self::ID_USER_1, 3.14, 'пополнение счета');
        $this->hBilling->addUserRuble(self::ID_USER_1, 6.28, 'еще одно пополнение счета');
        $this->hBilling->addUserBonus(self::ID_USER_1, 0.31, 'зачисление бонусов');

        // пользователь 2
        $this->hBilling->addUserRuble(self::ID_USER_2, 3.15, 'пополнение счета');
    }

    public function tearDown(): void
    {
        unset($this->hBilling);
    }
}