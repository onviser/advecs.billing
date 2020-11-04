<?php declare(strict_types=1);

namespace Advecs\Billing;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Account\User;
use Advecs\Billing\Posting\Posting;
use Advecs\Billing\Storage\StorageInterface;

/**
 * Class Billing
 * @package Advecs\Billing
 */
class Billing implements BillingInterface
{
    /** @var StorageInterface */
    protected $hStorage;

    /**
     * @param int $id
     * @return float
     */
    public function getUserBalanceRuble(int $id): float
    {
        $hUser = $this->hStorage->getAccount($id, Account::TYPE_USER);
        return $hUser->getBalance();
    }

    /**
     * @param int $id
     * @return float
     */
    public function getUserBalanceBonus(int $id): float
    {
        /** @var User $hUser */
        $hUser = $this->hStorage->getAccount($id, Account::TYPE_USER);
        return $hUser->getBalanceBonus();
    }

    /**
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addUserRuble(int $id, float $amount, string $comment = 'пополнение счета'): bool
    {
        $hUser = $this->hStorage->getAccount($id, Account::TYPE_USER);
        $hPosting = (new Posting($amount, $comment))
            ->setTo($hUser);
        return $this->hStorage->addRuble($hUser, $hPosting);
    }

    /**
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addUserBonus(int $id, float $amount, string $comment = 'зачисление бонусов'): bool
    {
        /** @var User $hUser */
        $hUser = $this->hStorage->getAccount($id, Account::TYPE_USER);
        $hPosting = (new Posting($amount, $comment))
            ->setTo($hUser);
        return $this->hStorage->addBonus($hUser, $hPosting);
    }

    /**
     * @param StorageInterface $hStorage
     * @return $this
     */
    public function setStorage(StorageInterface $hStorage): self
    {
        $this->hStorage = $hStorage;
        return $this;
    }
}