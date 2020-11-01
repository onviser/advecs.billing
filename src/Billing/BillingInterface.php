<?php declare(strict_types=1);

namespace Advecs\Billing;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Posting\Posting;
use Advecs\Billing\Storage\StorageInterface;

interface BillingInterface
{
    /**
     * @param Account $hAccount
     * @return float
     */
    public function getBalance(Account $hAccount): float;

    /**
     * @param Account $hAccount
     * @param Posting $hPosting
     * @return bool
     */
    public function moneyIn(Account $hAccount, Posting $hPosting): bool;

    /**
     * @param Account $hAccount
     * @param Posting $hPosting
     * @return bool
     */
    public function moneyOut(Account $hAccount, Posting $hPosting): bool;

    /**
     * @param Account $hAccountFrom
     * @param Account $hAccountTo
     * @param Posting $hPosting
     * @return bool
     */
    public function moneyTransfer(Account $hAccountFrom, Account $hAccountTo, Posting $hPosting): bool;

    /**
     * @param StorageInterface $hStorage
     * @return $this
     */
    public function setStorage(StorageInterface $hStorage);
}