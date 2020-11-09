<?php declare(strict_types=1);

require_once '../vendor/autoload.php';

use Advecs\Billing\Billing;
use Advecs\Billing\Exception\BillingException;
use Advecs\Billing\Exception\MySQLException;
use Advecs\Billing\Storage\MySQLStorage;

$config = require_once 'config.php';

try {
    $hBilling = (new Billing())
        ->setStorage(new MySQLStorage(
            $config['mysql']['host'],
            $config['mysql']['user'],
            $config['mysql']['password'],
            $config['mysql']['database'],
            $config['mysql']['port']
        ));

    $user1 = 1;
    $hBilling->addUserRuble($user1, 1.11, 'пополнение счета');
    $hBilling->addUserBonus($user1, 3.33, 'пополнение бонусного счета');

    $user2 = 2;
    $hBilling->addUserRuble($user2, 2.22, 'пополнение счета');
    $hBilling->addUserBonus($user2, 4.44, 'пополнение бонусного счета');

    // пересчет баланса
    $hBilling->reCountUser($user1);
    $hBilling->reCountUser($user2);

    // перевод со счета на счет
    $hBilling->transferUserRuble($user1, $user2, 0.10, 'перевод средств со счета на счет, ' . $user1 . ' -> ' . $user2);
    $hBilling->transferUserRuble($user1, $user2, 0.20, 'перевод средств со счета на счет, ' . $user1 . ' -> ' . $user2);
    $hBilling->transferUserRuble($user2, $user1, 0.30, 'перевод средств со счета на счет, ' . $user2 . ' -> ' . $user1);

    echo "баланс пользователя в рублях [{$user1}]: " . $hBilling->getUserBalanceRuble($user1) . PHP_EOL;
    echo "баланс пользователя в бонусах [{$user1}]: " . $hBilling->getUserBalanceBonus($user1) . PHP_EOL;
    echo "баланс пользователя в рублях [{$user2}]: " . $hBilling->getUserBalanceRuble($user2) . PHP_EOL;
    echo "баланс пользователя в бонусах [{$user2}]: " . $hBilling->getUserBalanceBonus($user2) . PHP_EOL;

} catch (MySQLException $hException) {
    echo 'ошибка: ' . $hException->getMessage() . PHP_EOL;
    if ($hException->getError() !== '') {
        echo ' - сообщение: ' . $hException->getError() . PHP_EOL;
    }
    if ($hException->getErrorNumber() > 0) {
        echo ' - код ошибки: ' . $hException->getErrorNumber() . PHP_EOL;
    }
    if ($hException->getSQL() !== '') {
        echo ' - запрос: ' . $hException->getSQL() . PHP_EOL;
    }
} catch (BillingException|Exception $hException) {
    echo 'ошибка: ' . $hException->getMessage() . PHP_EOL;
} catch (Error $hError) {
    echo 'ошибка: ' . $hError->getMessage() . PHP_EOL;
} finally {
    unset($hBilling);
}