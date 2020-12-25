<?php declare(strict_types=1);

namespace Advecs\Billing\PSCB;

class PSCBOrder
{
    const STATUS_CONFIRM = 'CONFIRM';
    const STATUS_REJECT = 'REJECT';

    /** @var int */
    protected $id = 0;

    /** @var int */
    protected $account = 0;

    /** @var float */
    protected $amount = 0.0;

    /** @var string */
    protected $state = '';

    /** @var string */
    protected $method = '';

    /** @var string */
    protected $json = '';

    /** @var string */
    protected $action = '';

    /** @var string */
    protected $error = '';

    /**
     * PSCBOrder constructor.
     * @param int $id
     * @param int $account
     * @param float $amount
     * @param string $state
     * @param string $method
     * @param string $json
     */
    public function __construct(int $id, int $account, float $amount, string $state, string $method, string $json)
    {
        $this->id = $id;
        $this->account = $account;
        $this->amount = $amount;
        $this->state = $state;
        $this->method = $method;
        $this->json = $json;
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

    /** @return string */
    public function getState(): string
    {
        return $this->state;
    }

    /** @return string */
    public function getMethod(): string
    {
        return $this->method;
    }

    /** @return string */
    public function getJSON(): string
    {
        return $this->json;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    /** @return string */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $error
     * @return $this
     */
    public function setError(string $error): self
    {
        $this->error = $error;
        return $this;
    }

    /** @return string */
    public function getError(): string
    {
        return $this->error;
    }
}