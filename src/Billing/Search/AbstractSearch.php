<?php declare(strict_types=1);

namespace Advecs\Billing\Search;

/**
 * Class AbstractSearch
 * @package Advecs\Billing\Search
 */
abstract class AbstractSearch
{
    /** @return int */
    protected $account = 0;

    /** @var int */
    protected $offset = 0;

    /** @var int */
    protected $limit = 100;

    /** @var int */
    protected $total = 0;

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
     * @param int $total
     * @return $this
     */
    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }

    /** @return int */
    public function getTotal(): int
    {
        return $this->total;
    }
}