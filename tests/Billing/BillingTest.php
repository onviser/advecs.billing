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

    /** @return bool */
    public function testUserAdd(): bool
    {
        $hBilling = $this->getBilling();

        // пользователь 1
        $hBilling->addUserRuble(self::ID_USER_1, 3.14, 'пополнение счета');
        $hBilling->addUserRuble(self::ID_USER_1, 6.28, 'еще одно пополнение счета');
        $hBilling->addUserBonus(self::ID_USER_1, 0.31, 'зачисление бонусов');
        $this->assertEquals(9.42, $hBilling->getUserBalanceRuble(self::ID_USER_1));
        $this->assertEquals(0.31, $hBilling->getUserBalanceBonus(self::ID_USER_1));

        // пользователь 2
        $hBilling->addUserRuble(self::ID_USER_2, 3.15, 'пополнение счета');
        $this->assertEquals(3.15, $hBilling->getUserBalanceRuble(self::ID_USER_2));
        $this->assertEquals(0, $hBilling->getUserBalanceBonus(self::ID_USER_2));

        return true;
    }

    /** @return bool */
    public function testUserTransfer(): bool
    {
        $hBilling = $this->getBilling();

        $hBilling->addUserRuble(self::ID_USER_1, 10, 'пополнение счета 1');
        $hBilling->addUserRuble(self::ID_USER_2, 20, 'пополнение счета 2');

        $hBilling->transferUserRuble(self::ID_USER_1, self::ID_USER_2, 2.5, 'перевод средств');
        $this->assertEquals(7.50, $hBilling->getUserBalanceRuble(self::ID_USER_1));
        $this->assertEquals(22.5, $hBilling->getUserBalanceRuble(self::ID_USER_2));

        $hBilling->transferUserRuble(self::ID_USER_2, self::ID_USER_1, 1.5, 'перевод средств обратно');
        $this->assertEquals(9, $hBilling->getUserBalanceRuble(self::ID_USER_1));
        $this->assertEquals(21, $hBilling->getUserBalanceRuble(self::ID_USER_2));

        return true;
    }

    /** @return bool */
    public function testFirmAdd(): bool
    {
        $hBilling = $this->getBilling();
        $this->assertEquals(0, $hBilling->getFirmBalanceRuble(self::ID_FIRM_1));

        $hBilling->addFirmRuble(self::ID_FIRM_1, 7000);
        $this->assertEquals(7000, $hBilling->getFirmBalanceRuble(self::ID_FIRM_1));

        $hBilling->addFirmRuble(self::ID_FIRM_1, 3000);
        $this->assertEquals(10000, $hBilling->getFirmBalanceRuble(self::ID_FIRM_1));

        $this->assertEquals(0, $hBilling->getFirmBalanceRuble(self::ID_FIRM_2));
        $hBilling->addFirmRuble(self::ID_FIRM_2, 5000);
        $this->assertEquals(5000, $hBilling->getFirmBalanceRuble(self::ID_FIRM_2));

        return true;
    }

    /** @return bool */
    public function testFirmTransfer(): bool
    {
        $hBilling = $this->getBilling();
        $hBilling->addFirmRuble(self::ID_FIRM_1, 5000);
        $hBilling->addFirmRuble(self::ID_FIRM_2, 1000);

        $hBilling->transferFirmRuble(self::ID_FIRM_1, self::ID_FIRM_2, 500, 'перевод средств между фирмами');
        $this->assertEquals(4500.00, $hBilling->getFirmBalanceRuble(self::ID_FIRM_1));
        $this->assertEquals(1500, $hBilling->getFirmBalanceRuble(self::ID_FIRM_2));

        $hBilling->transferFirmRuble(self::ID_FIRM_2, self::ID_FIRM_1, 100, 'перевод средств между фирмами обратно');
        $this->assertEquals(4600, $hBilling->getFirmBalanceRuble(self::ID_FIRM_1));
        $this->assertEquals(1400, $hBilling->getFirmBalanceRuble(self::ID_FIRM_2));

        return true;
    }

    /** @return bool */
    public function testUserFirmTransfer(): bool
    {
        $hBilling = $this->getBilling();
        $hBilling->addUserRuble(self::ID_USER_1, 1000);
        $hBilling->addFirmRuble(self::ID_FIRM_1, 5000);

        $hBilling->transferUserFirmRuble(self::ID_USER_1, self::ID_FIRM_1, 450, 'перевод от пользователя фирме');
        $this->assertEquals(550, $hBilling->getUserBalanceRuble(self::ID_USER_1));
        $this->assertEquals(5450, $hBilling->getFirmBalanceRuble(self::ID_FIRM_1));

        return true;
    }

    /** @return bool */
    public function testFirmUserTransfer(): bool
    {
        $hBilling = $this->getBilling();
        $hBilling->addUserRuble(self::ID_USER_1, 1000);
        $hBilling->addFirmRuble(self::ID_FIRM_1, 5000);

        $hBilling->transferFirmUserRuble(self::ID_FIRM_1, self::ID_USER_1, 400, 'перевод от фирмы пользователю');
        $this->assertEquals(1400, $hBilling->getUserBalanceRuble(self::ID_USER_1));
        $this->assertEquals(4600, $hBilling->getFirmBalanceRuble(self::ID_FIRM_1));

        return true;
    }

    /** @return Billing */
    public function getBilling(): Billing
    {
        return (new Billing())
            ->setStorage(new MemoryStorage());
    }
}