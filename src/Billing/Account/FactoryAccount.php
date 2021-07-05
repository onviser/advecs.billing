<?php declare(strict_types=1);

namespace Advecs\Billing\Account;

/**
 * Class FactoryAccount
 * @package Advecs\Billing\Account
 */
class FactoryAccount
{
    /**
     * @param int $type
     * @param int $id
     * @param float|int $balance
     * @param float|int $balanceBonus
     * @return Firm|System|User
     */
    public static function getInstance(int $type, int $id, float $balance = 0, float $balanceBonus = 0): Account
    {
        switch ($type) {
            case Account::TYPE_FIRM:
                return new Firm($id, $balance, $balanceBonus);
                break;
            case Account::TYPE_SYSTEM:
                return new System($id, $balance, $balanceBonus);
                break;
        }
        return new User($id, $balance, $balanceBonus);
    }
}