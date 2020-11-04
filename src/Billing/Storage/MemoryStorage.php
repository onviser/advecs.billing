<?php declare(strict_types=1);

namespace Advecs\Billing\Storage;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Posting\Posting;

class MemoryStorage implements StorageInterface
{
    /** @var array */
    protected $account = [];

    /** @var Posting[] */
    protected $posting = [];

    /**
     * @param Account $hAccount
     * @param Posting $hPosting
     * @return bool
     */
    public function moneyIn(Account $hAccount, Posting $hPosting): bool
    {
        $this->checkAccount($hAccount);
        $hPosting->setTo($hAccount);
        $this->posting[$hAccount->getType()][$hAccount->getId()][] = $hPosting;
        $hAccount->changeBalance($hPosting);
        return true;
    }

    /**
     * @param Account $hFrom
     * @param Account $hTo
     * @param Posting $hPosting
     * @return bool
     */
    public function moneyTransfer(Account $hFrom, Account $hTo, Posting $hPosting): bool
    {
        $this->checkAccount($hFrom);
        $this->checkAccount($hTo);
        return true;
    }

    /**
     * @param Account $hAccount
     * @return bool
     */
    protected function checkAccount(Account $hAccount)
    {
        if (!array_key_exists($hAccount->getType(), $this->account)) {
            $this->account[$hAccount->getType()] = [];
        }
        if (!array_key_exists($hAccount->getId(), $this->account[$hAccount->getType()])) {
            $this->account[$hAccount->getType()][$hAccount->getId()] = $hAccount;
        }
        return true;
    }
}