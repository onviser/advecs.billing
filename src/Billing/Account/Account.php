<?php declare(strict_types=1);

namespace Advecs\Billing\Account;

/**
 * Class Account
 * @package Advecs\Billing\Account
 */
abstract class Account
{
    const TYPE_USER = 1;
    const TYPE_FIRM = 2;

    /** @var int */
    protected $id = 0;

    /** @var float */
    protected $balance = 0.0;

    /** @var float */
    protected $balanceBonus = 0.0;

    /**
     * Account constructor.
     * @param int $id
     * @param float|int $balance
     * @param float|int $balanceBonus
     */
    public function __construct(int $id, float $balance = 0, float $balanceBonus = 0)
    {
        $this->id = $id;
        $this->balance = $balance;
        $this->balanceBonus = $balanceBonus;
    }

    /** @return int */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param float $balance
     * @return $this
     */
    public function setBalance(float $balance): self
    {
        $this->balance = $balance;
        return $this;
    }

    /** @return float */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @param float $amount
     * @return float
     */
    public function changeBalance(float $amount): float
    {
        $this->balance += $amount;
        return $this->balance;
    }

    /**
     * @param float $balance
     * @return $this
     */
    public function setBalanceBonus(float $balance): self
    {
        $this->balanceBonus = $balance;
        return $this;
    }

    /** @return float */
    public function getBalanceBonus(): float
    {
        return $this->balanceBonus;
    }

    /**
     * @param float $amount
     * @return float
     */
    public function changeBalanceBonus(float $amount): float
    {
        $this->balanceBonus += $amount;
        return $this->balanceBonus;
    }

    /** @return int */
    public abstract function getType(): int;
}