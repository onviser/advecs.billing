<?php declare(strict_types=1);
require_once '../vendor/autoload.php';

use Advecs\App\Config\Config;
use Advecs\Billing\Billing;
use Advecs\Billing\Exception\BillingException;
use Advecs\Billing\Exception\MySQLException;
use Advecs\Billing\Search\Search;
use Advecs\Billing\Storage\MySQLStorage;
use Advecs\Billing\Search\SearchAccount;

$dir = '..' . DIRECTORY_SEPARATOR;
$dir .= 'app' . DIRECTORY_SEPARATOR;
$dir .= 'config' . DIRECTORY_SEPARATOR;
$hConfig = new Config(
    Config::getEnvFromFile($dir . '.env'),
    Config::getParamFromFile($dir . 'config.php')
);

try {
    $hBilling = (new Billing())
        ->setStorage(new MySQLStorage(
            $hConfig->get('db-billing.host'),
            $hConfig->get('db-billing.user'),
            $hConfig->get('db-billing.pass'),
            $hConfig->get('db-billing.name'),
            intval($hConfig->get('db-billing.port'))
        ));

    $user1 = 1;
    $hBilling->addUserRuble($user1, 1.0, 'пополнение счета пользователя');
    $hBilling->addUserBonus($user1, 3.0, 'пополнение бонусного счета пользователя');

    $user2 = 2;
    $hBilling->addUserRuble($user2, 2.0, 'пополнение счета пользователя');
    $hBilling->addUserBonus($user2, 4.0, 'пополнение бонусного счета пользователя');

    $firm1 = 11;
    $hBilling->addFirmRuble($firm1, 500, 'пополнение счета фирмы');

    $firm2 = 22;
    $hBilling->addFirmRuble($firm2, 700, 'пополнение счета фирмы');

    // перевод со счета на счет (пользователи)
    $hBilling->transferUserRuble($user1, $user2, 0.10, 'перевод средств со счета на счет, ' . $user1 . ' -> ' . $user2);
    $hBilling->transferUserRuble($user2, $user1, 0.50, 'перевод средств со счета на счет, ' . $user2 . ' -> ' . $user1);

    // перевод со счета на счет (фирмы)
    $hBilling->transferFirmRuble($firm1, $firm2, 100, 'перевод средств со счета на счет, ' . $firm1 . ' -> ' . $firm2);
    $hBilling->transferFirmRuble($firm2, $firm1, 150, 'перевод средств со счета на счет, ' . $firm2 . ' -> ' . $firm1);

    $hBilling->transferUserFirmRuble($user1, $firm1, 0.75, 'перевод средств от пользователя к фирме, ' . $user1 . ' -> ' . $firm1);
    $hBilling->transferUserFirmRuble($user1, $firm1, 0.25, 'перевод средств от пользователя к фирме, ' . $user1 . ' -> ' . $firm1);
    $hBilling->transferFirmUserRuble($firm1, $user1, 70, 'перевод средств от фирмы к пользователю, ' . $firm1 . ' -> ' . $user1);
    $hBilling->transferFirmUserRuble($firm1, $user1, 10, 'перевод средств от фирмы к пользователю, ' . $firm1 . ' -> ' . $user1);

    // пересчет баланса (пользователи)
    $hBilling->reCountUser($user1);
    $hBilling->reCountUser($user2);

    // пересчет баланса (фирмы)
    $hBilling->reCountFirm($firm1);
    $hBilling->reCountFirm($firm2);

    // получение проводок
    $hSearch = (new Search())
        ->setAccount($hBilling->getAccountUser($user1)->getId())
        ->setAmount(0.5, 1)
        ->setComment('2 -> 1')
        ->setLimit(0, 10);
    $postings = $hBilling->getPosting($hSearch);
    foreach ($postings as $hPosting) {
        echo " - проводка {$hPosting->getId()}: {$hPosting->getAmount()} руб., {$hPosting->getComment()}" . PHP_EOL;
    }

    // получение бонусных проводок
    $hSearch = (new Search())
        ->setLimit(0, 10);
    $postings = $hBilling->getPostingBonus($hSearch);
    foreach ($postings as $hPosting) {
        echo " - проводка {$hPosting->getId()}: {$hPosting->getAmount()} бон., {$hPosting->getComment()}" . PHP_EOL;
    }
    echo " - кол-во проводок: {$hSearch->getAmountPosting()}" . PHP_EOL;

    $hSearchAccount = new SearchAccount();
    $account = $hBilling->searchAccount($hSearchAccount);
    foreach ($account as $hAccount) {
        echo " - аккаунт {$hAccount->getId()}: {$hAccount->getBalance()} руб., {$hAccount->getBalanceBonus()} бон." . PHP_EOL;
    }
    echo " - кол-во аккаунтов: {$hSearchAccount->getAmount()}" . PHP_EOL;

    echo "баланс пользователя в рублях [{$user1}]: " . $hBilling->getUserBalanceRuble($user1) . PHP_EOL;
    echo "баланс пользователя в бонусах [{$user1}]: " . $hBilling->getUserBalanceBonus($user1) . PHP_EOL;
    echo "баланс пользователя в рублях [{$user2}]: " . $hBilling->getUserBalanceRuble($user2) . PHP_EOL;
    echo "баланс пользователя в бонусах [{$user2}]: " . $hBilling->getUserBalanceBonus($user2) . PHP_EOL;
    echo "баланс фирмы в рублях [{$firm1}]: " . $hBilling->getFirmBalanceRuble($firm1) . PHP_EOL;
    echo "баланс фирмы в рублях [{$firm2}]: " . $hBilling->getFirmBalanceRuble($firm2) . PHP_EOL;

    $hAccount = $hBilling->getAccountUser($user1);
    $user = $hBilling->getIdUser($hAccount->getId());
    echo "счет [{$hAccount->getId()}]: пользователь: " . $user . PHP_EOL;

    $hAccount = $hBilling->getAccountUser($user2);
    $user = $hBilling->getIdUser($hAccount->getId());
    echo "счет [{$hAccount->getId()}]: пользователь: " . $user . PHP_EOL;
}
catch (MySQLException $hException) {
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
}
catch (BillingException $hException) {
    echo 'ошибка: ' . $hException->getMessage() . PHP_EOL;
}
catch (Exception $hException) {
    echo 'ошибка: ' . $hException->getMessage() . PHP_EOL;
}
catch (Error $hError) {
    echo 'ошибка: ' . $hError->getMessage() . PHP_EOL;
}
finally {
    unset($hBilling);
}