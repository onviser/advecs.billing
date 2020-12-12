<?php declare(strict_types=1);

namespace Tests\Billing;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Billing;
use Advecs\Billing\Exception\BillingException;
use Advecs\Billing\Exception\NotEnoughException;
use Advecs\Billing\Search\Search;
use Advecs\Billing\Storage\MemoryStorage;
use PHPUnit\Framework\TestCase;

class BillingTest extends TestCase
{
    const ID_USER_1 = 1;
    const ID_USER_2 = 2;
    const ID_FIRM_1 = 1;
    const ID_FIRM_2 = 2;
    const ID_EXTERNAL_1 = 111;
    const ID_EXTERNAL_2 = 222;

    /** @return bool */
    public function testGetIdUser(): bool
    {
        $hBilling = $this->getBilling();

        // пользователь 1
        $hBilling->addUserRuble(self::ID_USER_1, 3.14, 'пополнение счета');
        $hBilling->addUserRuble(self::ID_USER_1, 6.28, 'еще одно пополнение счета');
        $hBilling->addUserBonus(self::ID_USER_1, 0.31, 'зачисление бонусов');

        // пользователь 2
        $hBilling->addUserRuble(self::ID_USER_2, 1.11, 'пополнение счета');
        $hBilling->addUserRuble(self::ID_USER_2, 2.22, 'пополнение счета');

        $hAccount1 = $hBilling->getAccountUser(self::ID_USER_1);
        $hAccount1->setIdExternal(self::ID_EXTERNAL_1);
        //$this->assertEquals(self::ID_USER_1, $hBilling->getIdUser(self::ID_EXTERNAL_1));

        $hBilling->getAccountUser(self::ID_USER_2)->setIdExternal(self::ID_EXTERNAL_2);
        //$this->assertEquals(self::ID_USER_2, $hBilling->getIdUser(self::ID_EXTERNAL_2));

        return true;
    }

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

    /** @return bool */
    public function testGetPosting(): bool
    {
        $hBilling = $this->getBilling();

        $time1 = time();
        $hBilling->addUserRuble(self::ID_USER_1, 1000, 'пополнение u1-1');
        $hBilling->addUserRuble(self::ID_USER_1, 200, 'пополнение u1-2');
        $hBilling->addUserRuble(self::ID_USER_1, 500, 'пополнение u1-3');
        $hBilling->addUserRuble(self::ID_USER_1, 300, 'пополнение u1-4');

        sleep(1);
        $time2 = time();
        $hBilling->addUserRuble(self::ID_USER_2, 500, 'пополнение u2-1');
        $hBilling->addUserRuble(self::ID_USER_2, 200, 'пополнение u2-2');

        sleep(1);
        $time3 = time();
        $hBilling->addFirmRuble(self::ID_FIRM_1, 5000, 'пополнение f1-1');
        $hBilling->addFirmRuble(self::ID_FIRM_1, 2000, 'пополнение f1-2');

        $hBilling->addFirmRuble(self::ID_FIRM_2, 1300, 'пополнение f2-1');
        $hBilling->addFirmRuble(self::ID_FIRM_2, 1400, 'пополнение f2-2');

        $posting = $hBilling->getPosting(new Search());
        $this->assertCount(10, $posting);

        $hSearch = (new Search(self::ID_USER_1, Account::TYPE_USER))
            ->setAmount(200, 500);
        $posting = $hBilling->getPosting($hSearch);
        $this->assertCount(3, $posting);
        $this->assertEquals(3, $hSearch->getAmountPosting());

        $posting = $hBilling->getPosting(
            (new Search())
                ->setAccountType(Account::TYPE_USER)
                ->setAmount(200, 500));
        $this->assertCount(5, $posting);

        $posting = $hBilling->getPosting(
            (new Search())
                ->setAccount(self::ID_USER_1)
                ->setAmount(200, 5000));
        $this->assertCount(6, $posting);

        $posting = $hBilling->getPosting(
            (new Search())
                ->setComment('u1'));
        $this->assertCount(4, $posting);

        $posting = $hBilling->getPosting(
            (new Search())
                ->setComment('u2'));
        $this->assertCount(2, $posting);

        $posting = $hBilling->getPosting(
            (new Search())
                ->setAmount(200, 300)
                ->setComment('u'));
        $this->assertCount(3, $posting);

        $posting = $hBilling->getPosting(
            (new Search())
                ->setTime($time1, $time2));
        $this->assertCount(4, $posting);

        $posting = $hBilling->getPosting(
            (new Search())
                ->setTime($time2, $time3));
        $this->assertCount(2, $posting);

        $posting = $hBilling->getPosting(
            (new Search())
                ->setAccountType(Account::TYPE_USER)
                ->setLimit(2, 4));
        $this->assertCount(4, $posting);

        return true;
    }

    /**
     * @return bool
     * @throws BillingException
     */
    public function testExceptionUserRuble(): bool
    {
        $this->expectException(NotEnoughException::class);
        $hBilling = $this->getBilling();
        $hBilling->addUserRuble(self::ID_USER_1, 500, 'пополнение u1-1');
        $hBilling->addUserRuble(self::ID_USER_2, 500, 'пополнение u2-1');
        $hBilling->transferUserRuble(self::ID_USER_1, self::ID_USER_2, 600, 'списание');
        return true;
    }

    /**
     * @return bool
     * @throws BillingException
     */
    public function testExceptionUserFirmRuble(): bool
    {
        $this->expectException(NotEnoughException::class);
        $hBilling = $this->getBilling();
        $hBilling->addUserRuble(self::ID_USER_1, 500, 'пополнение u1-1');
        $hBilling->addUserRuble(self::ID_USER_2, 500, 'пополнение u2-1');
        $hBilling->transferUserFirmRuble(self::ID_USER_1, self::ID_FIRM_2, 600, 'списание');
        return true;
    }

    /**
     * @return bool
     * @throws BillingException
     */
    public function testReCount(): bool
    {
        $hBilling = $this->getBilling();
        $hBilling->addUserRuble(self::ID_USER_1, 400, 'пополнение u1-1');
        $hBilling->addUserRuble(self::ID_USER_1, 600, 'пополнение u1-2');
        $hBilling->addUserRuble(self::ID_USER_1, 900, 'пополнение u1-3');
        $hBilling->addUserRuble(self::ID_USER_2, 10, 'пополнение бонусов u1-1');
        $hBilling->addUserRuble(self::ID_USER_2, 20, 'пополнение бонусов u1-2');
        $hBilling->addUserRuble(self::ID_USER_2, 30, 'пополнение бонусов u1-2');
        $hBilling->transferUserRuble(self::ID_USER_1, self::ID_USER_2, 140, 'тестовый перевод');
        $this->assertEquals(true, $hBilling->reCountUser(self::ID_USER_1));
        $this->assertEquals(true, $hBilling->reCountUser(self::ID_USER_2));

        $hBilling->addUserBonus(self::ID_USER_1, 400, 'пополнение u1-1');
        $hBilling->addUserBonus(self::ID_USER_1, 600, 'пополнение u1-2');
        $hBilling->addUserBonus(self::ID_USER_1, 900, 'пополнение u1-3');
        $this->assertEquals(true, $hBilling->reCountUser(self::ID_USER_1));

        return true;
    }

    /** @return Billing */
    public function getBilling(): Billing
    {
        return (new Billing())
            ->setStorage(new MemoryStorage());
    }
}