<?php declare(strict_types=1);

namespace Advecs\Billing\Storage;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Account\User;
use Advecs\Billing\Posting\Posting;

/**
 * Interface StorageInterface
 * @package Advecs\Billing\Storage
 */
interface StorageInterface
{
    /**
     * @param Account $hAccount
     * @return float
     */
    public function getBalanceRuble(Account $hAccount): float;

    /**
     * @param User $hUser
     * @return float
     */
    public function getBalanceBonus(User $hUser): float;

    /**
     * @param Account $hAccount
     * @param Posting $hPosting
     * @return bool
     */
    public function addRuble(Account $hAccount, Posting $hPosting): bool;

    /**
     * @param User $hUser
     * @param Posting $hPosting
     * @return bool
     */
    public function addBonus(User $hUser, Posting $hPosting): bool;
}