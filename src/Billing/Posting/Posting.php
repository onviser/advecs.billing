<?php declare(strict_types=1);

namespace Advecs\Billing\Posting;

use Advecs\Billing\Account\Account;

/**
 * Class Posting
 * @package Advecs\Billing\Posting
 */
class Posting
{
    /** @var int */
    protected $id = 0;

    /** @var int */
    protected $idAccount = 0;

    /** @var int */
    protected $idAccountFrom = 0;

    /** @var int */
    protected $idAccountTo = 0;

    /** @var float */
    protected $amount = 0.0;

    /** @var float */
    protected $time = 0.0;

    /** @var string */
    protected $comment = '';

    /** @var ?Account */
    protected $hAccount;

    /** @var ?Account */
    protected $hFrom;

    /** @var ?Account */
    protected $hTo;

    /**
     * Posting constructor.
     * @param float $amount
     * @param string $comment
     * @param float|int $time
     */
    public function __construct(float $amount, string $comment = '', float $time = 0)
    {
        $this->amount = $amount;
        $this->comment = $comment;
        if ($time === 0) {
            $time = microtime(true);
        }
        $this->time = $time;
    }

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
     * @param int $idAccount
     * @param int $idAccountFrom
     * @param int $idAccountTo
     * @return $this
     */
    public function setIdAccount(int $idAccount, int $idAccountFrom = 0, int $idAccountTo = 0): self
    {
        $this->idAccount = $idAccount;
        $this->idAccountFrom = $idAccountFrom;
        $this->idAccountTo = $idAccountTo;
        return $this;
    }

    /** @return int */
    public function getIdAccount(): int
    {
        return $this->idAccount;
    }

    /** @return int */
    public function getIdAccountFrom(): int
    {
        return $this->idAccountFrom;
    }

    /** @return int */
    public function getIdAccountTo(): int
    {
        return $this->idAccountTo;
    }

    /**
     * @param Account|null $hAccount
     * @return $this
     */
    public function setAccount(?Account $hAccount): self
    {
        $this->hAccount = $hAccount;
        return $this;
    }

    /** @return Account|null */
    public function getAccount(): ?Account
    {
        return $this->hAccount;
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

    /** @return float */
    public function getTime(): float
    {
        return $this->time;
    }

    /** @return int */
    public function getDay(): int
    {
        return strtotime(date('Y-m-d', intval($this->time)));
    }
}