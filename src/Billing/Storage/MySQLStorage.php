<?php declare(strict_types=1);

namespace Advecs\Billing\Storage;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Posting\Posting;

class MySQLStorage implements StorageInterface
{

    public function getAccount(int $id, int $type = Account::TYPE_USER): Account
    {
        // TODO: Implement getAccount() method.
    }

    public function addRuble(Posting $hPostingCredit): bool
    {
        // TODO: Implement addRuble() method.
    }

    public function addBonus(Posting $hPostingCredit): bool
    {
        // TODO: Implement addBonus() method.
    }

    public function transferRuble(Posting $hPostingCredit): bool
    {
        // TODO: Implement transferRuble() method.
    }
}