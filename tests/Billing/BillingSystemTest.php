<?php declare(strict_types=1);

namespace Tests\Billing;

use Advecs\Billing\Account\Firm;
use Advecs\Billing\Account\System;
use Advecs\Billing\Account\User;
use Advecs\Billing\Billing;
use Advecs\Billing\Exception\BillingException;
use Advecs\Billing\Storage\MemoryStorage;
use PHPUnit\Framework\TestCase;

/**
 * BAC-120, тестирование системных счетов
 * Class BillingSystemTest
 * @package Tests\Billing
 */
class BillingSystemTest extends TestCase
{
    const ID_USER_1 = 1;
    const ID_FIRM_1 = 1;
    const ID_SYSTEM_1 = 1;
    const ID_SYSTEM_2 = 2;

    /**
     * @return bool
     * @throws BillingException
     */
    public function testAdd(): bool
    {
        $hBilling = $this->getBilling();

        $user1 = new User(self::ID_USER_1);
        $firm1 = new Firm(self::ID_FIRM_1);
        $system1 = new System(self::ID_SYSTEM_1);
        $system2 = new System(self::ID_SYSTEM_2);

        // пополнения
        $hBilling->addRuble($user1, 1, 'пополнение счета пользователя');
        $hBilling->addRuble($firm1, 10, 'пополнение счета фирмы');
        $hBilling->addRuble($system1, 3.14, 'пополнение системного счета');
        $hBilling->addRuble($system1, 3.14, 'еще одно пополнение системного счета');
        $hBilling->addRuble($system2, 2, 'пополнение системного счета');

        $this->assertEquals(1, $user1->getBalance());
        $this->assertEquals(10, $firm1->getBalance());
        $this->assertEquals(6.28, $system1->getBalance());
        $this->assertEquals(2, $system2->getBalance());

        // переводы
        $hBilling->transferRuble($firm1, $user1, 1, 'перевод пользователю');
        $hBilling->transferRuble($firm1, $system1, 1, 'перевод на системный счет');
        $hBilling->transferRuble($firm1, $system1, 2, 'перевод на системный счет');
        $hBilling->transferRuble($firm1, $system2, 3, 'перевод на системный счет');

        $this->assertEquals(2, $user1->getBalance());
        $this->assertEquals(3, $firm1->getBalance());
        $this->assertEquals(9.28, $system1->getBalance());
        $this->assertEquals(5, $system2->getBalance());

        // проверка имени
        $this->assertEquals(System::ACCOUNT_EGRN_NAME, $system1->getName(System::ACCOUNT_EGRN));

        return true;
    }

    /** @return Billing */
    public function getBilling(): Billing
    {
        return (new Billing())
            ->setStorage(new MemoryStorage());
    }
}