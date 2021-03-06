<?php declare(strict_types=1);

namespace Advecs\Billing;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Posting\Posting;
use Advecs\Billing\PSCB\PSCBNotify;
use Advecs\Billing\PSCB\PSCBPayment;
use Advecs\Billing\Search\Search;
use Advecs\Billing\Search\SearchAccount;
use Advecs\Billing\Search\SearchPayment;
use Advecs\Billing\Storage\StorageInterface;

/**
 * Interface BillingInterface
 * @package Advecs\Billing
 */
interface BillingInterface
{
    /**
     * Аккаунт пользователя в биллинге
     * @return Account|null
     * @var int $id
     */
    public function getAccountUser(int $id): ?Account;

    /**
     * возвращает id пользоватетеля по аккаунту
     * @param int $account
     * @return int
     */
    public function getIdUser(int $account): int;

    /**
     * Аккаунт фирмы в биллинге
     * @return Account|null
     * @var int $id
     */
    public function getAccountFirm(int $id): ?Account;

    /**
     * возвращает id фирмы по аккаунту
     * @param int $account
     * @return int
     */
    public function getIdFirm(int $account): int;

    /**
     * Баланс пользователя в рублях
     * @param int $id
     * @return float
     */
    public function getUserBalanceRuble(int $id): float;

    /**
     * Баланс пользователя в бонусах
     * @param int $id
     * @return float
     */
    public function getUserBalanceBonus(int $id): float;

    /**
     * Пополнение рублевого счета пользователя
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addUserRuble(int $id, float $amount, string $comment = 'пополнение счета'): bool;

    /**
     * Пополнение бонусного счета пользователя
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addUserBonus(int $id, float $amount, string $comment = 'зачисление бонусов'): bool;

    /**
     * Перевод средств между пользователями
     * @param int $from
     * @param int $to
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferUserRuble(int $from, int $to, float $amount, string $comment): bool;

    /**
     * Баланс фирмы
     * @param int $id
     * @return float
     */
    public function getFirmBalanceRuble(int $id): float;

    /**
     * Баланс фирмы
     * @param int $id
     * @return float
     */
    public function getFirmBalanceBonus(int $id): float;

    /**
     * Пополнение счета фирмы
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addFirmRuble(int $id, float $amount, string $comment = 'пополнение счета фирмы'): bool;

    /**
     * Пополнение бонусного счета пользователя
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addFirmBonus(int $id, float $amount, string $comment = 'зачисление бонусов'): bool;

    /**
     * Перевод средств от фирмы к фирме
     * @param int $from
     * @param int $to
     * @param float $amount
     * @param string $comment
     * @return bool
     * @throws Exception\BillingException
     */
    public function transferFirmRuble(int $from, int $to, float $amount, string $comment): bool;

    /**
     * Перевод средств от пользователя фирме
     * @param int $user
     * @param int $firm
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferUserFirmRuble(int $user, int $firm, float $amount, string $comment): bool;

    /**
     * Перевод бонусов от пользователя фирме
     * @param int $user
     * @param int $firm
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferUserFirmBonus(int $user, int $firm, float $amount, string $comment): bool;

    /**
     * Перевод средств от фирмы пользователю
     * @param int $firm
     * @param int $user
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferFirmUserRuble(int $firm, int $user, float $amount, string $comment): bool;

    /**
     * Пополнение аккаунта (пользователь, фирма, системный)
     * @param Account $hAccount
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addRuble(Account $hAccount, float $amount, string $comment = 'пополнение счета'): bool;

    /**
     * Зачисление бонусов (пользователь, фирма, системный)
     * @param Account $hAccount
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addBonus(Account $hAccount, float $amount, string $comment = 'зачисление бонусов'): bool;

    /**
     * @param Account $from
     * @param Account $to
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferRuble(Account $from, Account $to, float $amount, string $comment): bool;

    /**
     * @param Account $from
     * @param Account $to
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferBonus(Account $from, Account $to, float $amount, string $comment): bool;

    /**
     * Список проводок
     * @param Search $hSearch
     * @return Posting[]
     */
    public function getPosting(Search $hSearch): array;

    /**
     * Список проводок по бонусному счету
     * @param Search $hSearch
     * @return Posting[]
     */
    public function getPostingBonus(Search $hSearch): array;

    /**
     * Пересчет баланса (рубли, бонусы) пользователя
     * @param int $id
     * @return bool
     */
    public function reCountUser(int $id): bool;

    /**
     * Пересчет баланса (рубли, бонусы) фирмы
     * @param int $id
     * @return bool
     */
    public function reCountFirm(int $id): bool;

    /**
     * @param PSCBPayment $hPayment
     * @return bool
     */
    public function addPSCBPayment(PSCBPayment $hPayment): bool;

    /**
     * @param PSCBNotify $hPSCBNotify
     * @return bool
     */
    public function addPSCBNotify(PSCBNotify $hPSCBNotify): bool;

    /**
     * @param PSCBNotify $hPSCBNotify
     * @return array
     */
    public function processingPSCBNotify(PSCBNotify $hPSCBNotify): array;

    /**
     * @param StorageInterface $hStorage
     * @return $this
     */
    public function setStorage(StorageInterface $hStorage);

    /**
     * @param SearchAccount $hSearch
     * @return Account[]
     */
    public function searchAccount(SearchAccount $hSearch): array;

    /**
     * @param SearchPayment $hSearch
     * @return PSCBPayment[]
     */
    public function searchPayment(SearchPayment $hSearch): array;
}