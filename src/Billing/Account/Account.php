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

    /**
     * Account constructor.
     * @param int $id
     * @param float|int $balance
     */
    public function __construct(int $id, float $balance = 0)
    {
        $this->id = $id;
        $this->balance = $balance;
    }

    /** @return int */
    public function getId(): int
    {
        return $this->id;
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

    /** @return int */
    public abstract function getType(): int;
}