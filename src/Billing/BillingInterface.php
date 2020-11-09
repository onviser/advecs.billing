<?php declare(strict_types=1);

namespace Advecs\Billing;

use Advecs\Billing\Posting\Posting;
use Advecs\Billing\Search\Search;
use Advecs\Billing\Storage\StorageInterface;

/**
 * Interface BillingInterface
 * @package Advecs\Billing
 */
interface BillingInterface
{
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
     * Пополнение счета фирмы
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addFirmRuble(int $id, float $amount, string $comment = 'пополнение счета фирмы'): bool;

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
     * Перевод средств от фирмы пользователю
     * @param int $firm
     * @param int $user
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferFirmUserRuble(int $firm, int $user, float $amount, string $comment): bool;

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
     * @param StorageInterface $hStorage
     * @return $this
     */
    public function setStorage(StorageInterface $hStorage);
}