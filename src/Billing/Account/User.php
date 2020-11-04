<?php declare(strict_types=1);

namespace Advecs\Billing\Account;

/**
 * Class User
 * @package Advecs\Billing\Account
 */
class User extends Account
{
    /** @var float */
    protected $balanceBonus = 0.0;

    /** @return float */
    public function getBalanceBonus(): float
    {
        return $this->balanceBonus;
    }

    /**
     * @param float $amount
     * @return float
     */
    public function changeBalanceBonus(float $amount): float
    {
        $this->balanceBonus += $amount;
        return $this->balanceBonus;
    }

    /** @return int */
    public function getType(): int
    {
        return self::TYPE_USER;
    }
}