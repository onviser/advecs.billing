<?php declare(strict_types=1);

namespace Advecs\Billing;

use Advecs\Billing\Storage\StorageInterface;

/**
 * Class Billing
 * @package Advecs\Billing
 */
class Billing implements BillingInterface
{
    /** @var StorageInterface */
    protected $hStorage;

    /**
     * @param int $id
     * @return float
     */
    public function getUserBalanceRuble(int $id): float
    {
        return 0;
    }

    /**
     * @param int $id
     * @return float
     */
    public function getUserBalanceBonus(int $id): float
    {
        return 0;
    }

    /**
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addUserRuble(int $id, float $amount, string $comment = 'пополнение счета'): bool
    {
        return true;
    }

    /**
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addUserBonus(int $id, float $amount, string $comment = 'зачисление бонусов'): bool
    {
        return true;
    }

    /**
     * @param StorageInterface $hStorage
     * @return $this
     */
    public function setStorage(StorageInterface $hStorage): self
    {
        $this->hStorage = $hStorage;
        return $this;
    }
}