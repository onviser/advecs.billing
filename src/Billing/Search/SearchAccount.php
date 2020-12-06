<?php declare(strict_types=1);

namespace Advecs\Billing\Search;

class SearchAccount
{
    protected $account = 0;
    protected $accountType = 0;
    protected $external = 0;

    protected $offset = 0;
    protected $limit = 100;

    protected $amount = 0;

    /**
     * @param int $account
     * @return $this
     */
    public function setAccount(int $account): self
    {
        $this->account = $account;
        return $this;
    }

    /** @return int */
    public function getAccount(): int
    {
        return $this->account;
    }

    /**
     * @param int $accountType
     * @return $this
     */
    public function setAccountType(int $accountType): self
    {
        $this->accountType = $accountType;
        return $this;
    }

    /** @return int */
    public function getAccountType(): int
    {
        return $this->accountType;
    }

    /**
     * @param int $external
     * @return $this
     */
    public function setExternal(int $external): self
    {
        $this->external = $external;
        return $this;
    }

    /** @return int */
    public function getExternal(): int
    {
        return $this->external;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $offset = 1, int $limit = 100): self
    {
        $this->offset = $offset;
        $this->limit = $limit;
        return $this;
    }

    /** @return int */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /** @return int */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $amount
     * @return $this
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /** @return int */
    public function getAmount(): int
    {
        return $this->amount;
    }
}