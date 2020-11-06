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
    public function __construct(int $account, int $accountType = 0)
    {
        $this->account = $account;
        $this->accountType = $accountType;
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

    /**
     * @param string $comment
     * @return $this
     */
    public function setComment(string $comment = ''): self
    {
        $this->comment = $comment;
        return $this;
    }
}