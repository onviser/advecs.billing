<?php declare(strict_types=1);

namespace Advecs\Billing\Account;

/**
 * Class User
 * @package Advecs\Billing\Account
 */
class User extends Account
{
    /** @return int */
    public function getType(): int
    {
        return self::TYPE_USER;
    }
}