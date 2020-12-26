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