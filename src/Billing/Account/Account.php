<?php declare(strict_types=1);

namespace Advecs\Billing\Account;

abstract class Account
{
    const TYPE_USER = 1;
    const TYPE_FIRM = 2;

    /** @var int */
    protected $id = 0;

    /**
     * Account constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /** @return int */
    abstract function getType(): int;
}