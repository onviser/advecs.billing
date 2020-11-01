<?php declare(strict_types=1);

namespace Advecs\Billing\Posting;

class Posting
{
    /** @var float */
    protected $amount = 0.0;

    /** @var string */
    protected $comment = '';

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