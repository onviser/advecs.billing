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
     * @param int $id
     * @param int $type
     * @return Account
     */
    public function getAccount(int $id, int $type = Account::TYPE_USER): Account;

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