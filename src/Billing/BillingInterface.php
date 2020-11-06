<?php declare(strict_types=1);

namespace Advecs\Billing;

use Advecs\Billing\Storage\StorageInterface;

/**
 * Interface BillingInterface
 * @package Advecs\Billing
 */
interface BillingInterface
{
    /**
     * @param int $id
     * @return float
     */
    public function getUserBalanceRuble(int $id): float;

    /**
     * @param int $id
     * @return float
     */
    public function getUserBalanceBonus(int $id): float;

    /**
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addUserRuble(int $id, float $amount, string $comment = 'пополнение счета'): bool;

    /**
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addUserBonus(int $id, float $amount, string $comment = 'зачисление бонусов'): bool;

    /**
     * @param int $from
     * @param int $to
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferUserRuble(int $from, int $to, float $amount, string $comment): bool;

    /**
     * @param int $id
     * @return float
     */
    public function getFirmBalanceRuble(int $id): float;

    /**
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addFirmRuble(int $id, float $amount, string $comment = 'пополнение счета фирмы'): bool;

    /**
     * @param int $user
     * @param int $firm
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferUserFirmRuble(int $user, int $firm, float $amount, string $comment): bool;

    /**
     * @param int $firm
     * @param int $user
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferFirmUserRuble(int $firm, int $user, float $amount, string $comment): bool;

    /**
     * @param StorageInterface $hStorage
     * @return $this
     */
    public function setStorage(StorageInterface $hStorage);
}