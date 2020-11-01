<?php declare(strict_types=1);

namespace Advecs\Billing\Account;

class Firm extends Account
{
    /** @return int */
    public function getType(): int
    {
        return self::TYPE_FIRM;
    }
}