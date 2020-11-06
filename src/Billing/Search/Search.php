<?php declare(strict_types=1);

namespace Advecs\Billing\Search;

/**
 * Class Search
 * @package Advecs\Billing\Search
 */
class Search
{
    protected $account = 0;
    protected $accountType = 0;

    protected $offset = 1;
    protected $limit = 100;

    protected $timeFrom = 0;
    protected $timeTo = 0;

    protected $amountFrom = 0;
    protected $amountTo = 0;

    protected $comment = '';

    /**
     * Search constructor.
     * @param int $account
     * @param int $accountType
     */
    public function __construct(int $account = 0, int $accountType = 0)
    {
        $this->account = $account;
        $this->accountType = $accountType;
    }

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
     * @param int $timeFrom
     * @param int $timeTo
     * @return $this
     */
    public function setTime(int $timeFrom = 0, int $timeTo = 0): self
    {
        $this->timeFrom = $timeFrom;
        $this->timeTo = $timeTo;
        return $this;
    }

    /** @return int */
    public function getTimeFrom(): int
    {
        return $this->timeFrom;
    }

    /** @return int */
    public function getTimeTo(): int
    {
        return $this->timeTo;
    }

    /**
     * @param int $amountFrom
     * @param int $amountTo
     * @return $this
     */
    public function setAmount(int $amountFrom = 0, int $amountTo = 0): self
    {
        $this->amountFrom = $amountFrom;
        $this->amountTo = $amountTo;
        return $this;
    }

    /** @return int */
    public function getAmountFrom(): int
    {
        return $this->amountFrom;
    }

    /** @return int */
    public function getAmountTo(): int
    {
        return $this->amountTo;
    }

    /**
     * @param string $comment
     * @return $this
     */
    public function setComment(string $comment = ''): self
    {
        $this->comment = $comment;
        return $this;
    }

    /** @return string */
    public function getComment(): string
    {
        return $this->comment;
    }
}