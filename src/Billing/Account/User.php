<?php declare(strict_types=1);

namespace Advecs\Billing\Account;

class User extends Account
{
    /** @return int */
    public function getType(): int
    {
        return self::TYPE_USER;
    }
}