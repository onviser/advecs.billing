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