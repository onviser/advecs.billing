<?php declare(strict_types=1);

namespace Advecs\Billing\Account;

/**
 * Class System
 * @package Advecs\Billing\Account
 */
class System extends Account
{
    const ACCOUNT_EGRN = 1;
    const ACCOUNT_EGRN_NAME = 'Оплата ЕГРН';

    /** @return int */
    public function getType(): int
    {
        return self::TYPE_SYSTEM;
    }

    /**
     * @param int $id
     * @return string
     */
    public static function getName(int $id): string
    {
        $name = [
            self::ACCOUNT_EGRN => self::ACCOUNT_EGRN_NAME
        ];

        if (isset($name[$id])) {
            return $name[$id];
        }
        return '';
    }
}