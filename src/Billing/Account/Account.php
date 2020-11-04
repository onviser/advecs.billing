<?php declare(strict_types=1);

namespace Advecs\Billing\Account;

use Advecs\Billing\Posting\Posting;

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
     */
    public function __construct(int $id)
    {
        $this->id = $id;
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
     * @param Posting $hPosting
     * @return float
     */
    public function changeBalance(Posting $hPosting): float
    {
        $this->balance += $hPosting->getAmount();
        return $this->balance;
    }

    /** @return int */
    public abstract function getType(): int;
}