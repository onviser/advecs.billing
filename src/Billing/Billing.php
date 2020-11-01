<?php declare(strict_types=1);

namespace Advecs\Billing;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Posting\Posting;
use Advecs\Billing\Storage\StorageInterface;

class Billing implements BillingInterface
{
    /** @var StorageInterface */
    protected $hStorage;

    /**
     * @param Account $hAccount
     * @return float
     */
    public function getBalance(Account $hAccount): float
    {
        return 0;
    }

    /**
     * @param Account $hAccount
     * @param Posting $hPosting
     * @return bool
     */
    public function moneyIn(Account $hAccount, Posting $hPosting): bool
    {
        return false;
    }

    /**
     * @param Account $hAccount
     * @param Posting $hPosting
     * @return bool
     */
    public function moneyOut(Account $hAccount, Posting $hPosting): bool
    {
        return false;
    }

    /**
     * @param Account $hAccountFrom
     * @param Account $hAccountTo
     * @param Posting $hPosting
     * @return bool
     */
    public function moneyTransfer(Account $hAccountFrom, Account $hAccountTo, Posting $hPosting): bool
    {
        return false;
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