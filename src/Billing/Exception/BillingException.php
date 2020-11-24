<?php declare(strict_types=1);

namespace Advecs\Billing\Exception;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Posting\Posting;
use Exception;

/**
 * Class BillingException
 * @package Advecs\Billing\Exception
 */
class BillingException extends Exception
{
    /** @var Account */
    protected $account;

    /** @var Posting */
    protected $posting;

    /**
     * @param Account $account
     * @return $this
     */
    public function setAccount(Account $account): self
    {
        $this->account = $account;
        return $this;
    }

    /** @return Account */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Posting $posting
     * @return $this
     */
    public function setPosting(Posting $posting): self
    {
        $this->posting = $posting;
        return $this;
    }

    /** @return Posting */
    public function getPosting()
    {
        return $this->posting;
    }
}