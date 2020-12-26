<?php declare(strict_types=1);

namespace Tests\Billing;

use Advecs\Billing\Billing;
use Advecs\Billing\PSCB\PSCBNotify;
use Advecs\Billing\PSCB\PSCBOrder;
use Advecs\Billing\PSCB\PSCBPayment;
use Advecs\Billing\Storage\MemoryStorage;
use PHPUnit\Framework\TestCase;

class BillingPSCBNotifyTest extends TestCase
{
    const ID_ACCOUNT_1 = 1;
    const ID_ACCOUNT_2 = 2;
    const ID_ACCOUNT_3 = 2;

    /** @return bool */
    public function testNotify(): bool
    {
        $hBilling = $this->getBilling();

        $hBilling->addUserRuble(1, 1);
        $hBilling->addUserRuble(2, 2);
        $hBilling->addUserRuble(3, 3);

        $hBilling->addPSCBPayment((new PSCBPayment(self::ID_ACCOUNT_1, 10))->setId(1));
        $hBilling->addPSCBPayment((new PSCBPayment(self::ID_ACCOUNT_2, 20))->setId(2));
        $hBilling->addPSCBPayment((new PSCBPayment(self::ID_ACCOUNT_1, 30))->setId(3));
        $hBilling->addPSCBPayment((new PSCBPayment(self::ID_ACCOUNT_2, 40))->setId(4));

        $raw = base64_encode('raw');
        $json = base64_encode($this->getJSON_1());
        $hNotify = new PSCBNotify($raw, $json);
        $orders = $hBilling->processingPSCBNotify($hNotify);

        $amount = 0;
        $amountConfirm = 0;
        $amountReject = 0;
        foreach ($orders as $hOrder) {
            $amount++;
            switch ($hOrder->getAction()) {
                case PSCBOrder::STATUS_CONFIRM:
                    $amountConfirm++;
                    break;
                case PSCBOrder::STATUS_REJECT:
                    $amountReject++;
                    break;
            }
        }
        $this->assertEquals(5, $amount);
        $this->assertEquals(3, $amountConfirm);
        $this->assertEquals(1, $amountReject);

        $this->assertEquals(11, $hBilling->getAccountUser(self::ID_ACCOUNT_1)->getBalance());
        $this->assertEquals(62, $hBilling->getAccountUser(self::ID_ACCOUNT_2)->getBalance());

        return true;
    }

    /** @return Billing */
    protected function getBilling(): Billing
    {
        return (new Billing())
            ->setStorage(new MemoryStorage());
    }

    /** @return string */
    protected function getJSON_1(): string
    {
        return '{
  "payments": [
    {
      "orderId": "1",
      "showOrderId": "1",
      "paymentId": "229393950",
      "account": "' . self::ID_ACCOUNT_1 . '",
      "amount": 10,
      "state": "end",
      "marketPlace": 310417760,
      "paymentMethod": "ac",
      "stateDate": "2020-12-24T22:12:53.294+03:00",
      "lastError": {
        "code": "0",
        "subCode": "00",
        "description": "Платеж завершен"
      }
    },
    {
      "orderId": "2",
      "showOrderId": "2",
      "paymentId": "229393982",
      "account": "' . self::ID_ACCOUNT_2 . '",
      "amount": 20,
      "state": "end",
      "marketPlace": 310417760,
      "paymentMethod": "ac",
      "stateDate": "2020-12-24T22:20:59.401+03:00",
      "lastError": {
        "code": "0",
        "subCode": "00",
        "description": "Платеж завершен"
      }
    },
    {
      "orderId": "3",
      "showOrderId": "3",
      "paymentId": "229394049",
      "account": "' . self::ID_ACCOUNT_1 . '",
      "amount": 30,
      "state": "err",
      "marketPlace": 310417760,
      "paymentMethod": "ym",
      "stateDate": "2020-12-24T22:32:38.328+03:00"
    },
    {
      "orderId": "4",
      "showOrderId": "4",
      "paymentId": "229394055",
      "account": "' . self::ID_ACCOUNT_2 . '",
      "amount": 40,
      "state": "end",
      "marketPlace": 310417760,
      "paymentMethod": "ym",
      "stateDate": "2020-12-24T22:32:59.142+03:00"
    },
    {
      "orderId": "5",
      "showOrderId": "5",
      "paymentId": "229394060",
      "account": "' . self::ID_ACCOUNT_3 . '",
      "amount": 50,
      "state": "end",
      "marketPlace": 310417760,
      "paymentMethod": "ac",
      "stateDate": "2020-12-24T22:39:01.269+03:00",
      "lastError": {
        "code": "0",
        "subCode": "00",
        "description": "Платеж завершен"
      }
    }
  ]
}';
    }
}