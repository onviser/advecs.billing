<?php declare(strict_types=1);

namespace Advecs\Billing\Account;

use Advecs\Billing\Posting\Posting;

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
     * @param Posting $hPosting
     * @return float
     */
    public function changeBalanceBonus(Posting $hPosting): float
    {
        $this->balanceBonus += $hPosting->getAmount();
        return $this->balanceBonus;
    }

    /** @return int */
    public function getType(): int
    {
        return self::TYPE_USER;
    }
}