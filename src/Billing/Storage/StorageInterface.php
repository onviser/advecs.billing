<?php declare(strict_types=1);

namespace Advecs\Billing\Storage;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Posting\Posting;

interface StorageInterface
{
    /**
     * @param Account $hAccount
     * @param Posting $hPosting
     * @return bool
     */
    public function moneyIn(Account $hAccount, Posting $hPosting): bool;

    /**
     * @param Account $hFrom
     * @param Account $hTo
     * @param Posting $hPosting
     * @return bool
     */
    public function moneyTransfer(Account $hFrom, Account $hTo, Posting $hPosting): bool;
}