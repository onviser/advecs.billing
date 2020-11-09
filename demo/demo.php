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
    $hBilling->addUserRuble($user1, 3.14, 'пополнение счета');
    $hBilling->addUserBonus($user1, 2.15, 'пополнение бонусного счета');
    echo "баланс пользователя в рублях [{$user1}]: " . $hBilling->getUserBalanceRuble($user1) . PHP_EOL;
    echo "баланс пользователя в бонусах [{$user1}]: " . $hBilling->getUserBalanceBonus($user1) . PHP_EOL;

    $user2 = 2;
    $hBilling->addUserRuble($user2, 2.28, 'пополнение счета');
    $hBilling->addUserBonus($user2, 1.15, 'пополнение бонусного счета');
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