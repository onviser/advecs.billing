<?php declare(strict_types=1);

namespace Advecs\Billing\PSCB;

/**
 * Class PSCBPayment
 * @package Advecs\Billing\PSCB
 */
class PSCBPayment
{
    const TYPE_CARD = 'ac';
    const TYPE_ALFA_CLICK = 'alfa';

    const STATUS_NEW = 1;
    const STATUS_END = 2;

    /** @var int */
    protected $id = 0;

    /** @var int идентификатор аккаунта */
    protected $account = 0;

    /** @var float */
    protected $amount = 0.0;

    /** @var string */
    protected $comment = '';

    /** @var int */
    protected $status = self::STATUS_NEW;

    /** @var string */
    protected $type = self::TYPE_CARD;

    /** @var string */
    protected $json = '';

    /** @var int */
    protected $timeAdd = 0;

    /** @var int */
    protected $timeUpdate = 0;

    /**
     * PSCBPayment constructor.
     * @param int $account
     * @param float $amount
     * @param string $comment
     */
    public function __construct(int $account, float $amount, string $comment = '')
    {
        $this->account = $account;
        $this->amount = $amount;
        $this->comment = $comment;
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

    /** @return int */
    public function getAccount(): int
    {
        return $this->account;
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
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /** @return int */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /** @return string */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $json
     * @return $this
     */
    public function setJSON(string $json): self
    {
        $this->json = $json;
        return $this;
    }

    /** @return string */
    public function getJSON(): string
    {
        return $this->json;
    }

    /**
     * @param int $timeAdd
     * @param int $timeUpdate
     * @return $this
     */
    public function setTime(int $timeAdd, int $timeUpdate): self
    {
        $this->timeAdd = $timeAdd;
        $this->timeUpdate = $timeUpdate;
        return $this;
    }

    /** @return int */
    public function getTimeAdd(): int
    {
        return $this->timeAdd;
    }

    /** @return int */
    public function getTimeUpdate(): int
    {
        return $this->timeUpdate;
    }
}