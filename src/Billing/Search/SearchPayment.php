<?php declare(strict_types=1);

namespace Advecs\Billing\Search;

/**
 * Поиск платежей
 * Class SearchPayment
 * @package Advecs\Billing\Search
 */
class SearchPayment extends AbstractSearch
{
    /** @return int */
    protected $id = 0;

    /** @return int */
    protected $timeFrom = 0;

    /** @return int */
    protected $timeTo = 0;

    /** @return float */
    protected $amountFrom = 0.0;

    /** @return float */
    protected $amountTo = 0.0;

    /** @return int */
    protected $paymentStatus = 0;

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /** @return int */
    public function getId(): int
    {
        return $this->id;
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
     * @param float $amountFrom
     * @param float $amountTo
     * @return $this
     */
    public function setAmount(float $amountFrom = 0.0, float $amountTo = 0.0): self
    {
        $this->amountFrom = $amountFrom;
        $this->amountTo = $amountTo;
        return $this;
    }

    /** @return float */
    public function getAmountFrom(): float
    {
        return $this->amountFrom;
    }

    /** @return float */
    public function getAmountTo(): float
    {
        return $this->amountTo;
    }

    /**
     * @param int $paymentStatus
     * @return $this
     */
    public function setPaymentStatus(int $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;
        return $this;
    }

    /** @return int */
    public function getPaymentStatus(): int
    {
        return $this->paymentStatus;
    }
}