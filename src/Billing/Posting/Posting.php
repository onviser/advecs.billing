<?php declare(strict_types=1);

namespace Advecs\Billing\Posting;

use Advecs\Billing\Account\Account;

class Posting
{
    /** @var float */
    protected $amount = 0.0;

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
    }

    /**
     * @param Account $hFrom
     * @return $this
     */
    public function setFrom(Account $hFrom): self
    {
        $this->hFrom = $hFrom;
        return $this;
    }

    /**
     * @return Account|null
     */
    public function getFrom(): ?Account
    {
        return $this->hFrom;
    }

    /**
     * @param Account $hTo
     * @return $this
     */
    public function setTo(Account $hTo): self
    {
        $this->hTo = $hTo;
        return $this;
    }

    /**
     * @return Account|null
     */
    public function getTo(): ?Account
    {
        return $this->hTo;
    }

    /**
     * @return float
     */
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
}