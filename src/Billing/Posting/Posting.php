<?php declare(strict_types=1);

namespace Advecs\Billing\Posting;

use Advecs\Billing\Account\Account;

/**
 * Class Posting
 * @package Advecs\Billing\Posting
 */
class Posting
{
    /** @var float */
    protected $amount = 0.0;

    /** @var float */
    protected $time = 0.0;

    /** @var string */
    protected $comment = '';

    /** @var ?Account */
    protected $hFrom;

    /** @var ?Account */
    protected $hTo;

    /**
     * Posting constructor.
     * @param float $amount
     * @param string $comment
     */
    public function __construct(float $amount, string $comment = '')
    {
        $this->amount = $amount;
        $this->comment = $comment;
        $this->time = microtime(true);
    }

    /**
     * @param Account|null $hFrom
     * @return $this
     */
    public function setFrom(?Account $hFrom): self
    {
        $this->hFrom = $hFrom;
        return $this;
    }

    /** @return Account|null */
    public function getFrom(): ?Account
    {
        return $this->hFrom;
    }

    /**
     * @param Account|null $hTo
     * @return $this
     */
    public function setTo(?Account $hTo): self
    {
        $this->hTo = $hTo;
        return $this;
    }

    /** @return Account|null */
    public function getTo(): ?Account
    {
        return $this->hTo;
    }

    /** @return float */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param string $comment
     * @return $this
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    /** @return string */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param float $time
     * @return $this
     */
    public function setTime(float $time): self
    {
        $this->time = $time;
        return $this;
    }

    public function getTime(): float
    {
        return $this->time;
    }
}