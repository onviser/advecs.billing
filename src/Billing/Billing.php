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
        return $this->hStorage->addRuble($hPosting);
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
        return $this->hStorage->addBonus($hPosting);
    }

    /**
     * @param int $from
     * @param int $to
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferUserRuble(int $from, int $to, float $amount, string $comment): bool
    {
        $hFrom = $this->hStorage->getAccount($from, Account::TYPE_USER);
        $hTo = $this->hStorage->getAccount($to, Account::TYPE_USER);
        $hPosting = (new Posting($amount, $comment))
            ->setFrom($hFrom)
            ->setTo($hTo);
        return $this->hStorage->transferRuble($hPosting);
    }

    /**
     * @param int $id
     * @return float
     */
    public function getFirmBalanceRuble(int $id): float
    {
        $hFirm = $this->hStorage->getAccount($id, Account::TYPE_FIRM);
        return $hFirm->getBalance();
    }

    /**
     * @param int $from
     * @param int $to
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferFirmRuble(int $from, int $to, float $amount, string $comment): bool
    {
        $hFrom = $this->hStorage->getAccount($from, Account::TYPE_FIRM);
        $hTo = $this->hStorage->getAccount($to, Account::TYPE_FIRM);
        $hPosting = (new Posting($amount, $comment))
            ->setFrom($hFrom)
            ->setTo($hTo);
        return $this->hStorage->transferRuble($hPosting);
    }

    /**
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addFirmRuble(int $id, float $amount, string $comment = 'пополнение счета фирмы'): bool
    {
        $hFirm = $this->hStorage->getAccount($id, Account::TYPE_FIRM);
        $hPosting = (new Posting($amount, $comment))
            ->setTo($hFirm);
        return $this->hStorage->addRuble($hPosting);
    }

    /**
     * @param int $user
     * @param int $firm
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferUserFirmRuble(int $user, int $firm, float $amount, string $comment): bool
    {
        $hFrom = $this->hStorage->getAccount($user, Account::TYPE_USER);
        $hTo = $this->hStorage->getAccount($firm, Account::TYPE_FIRM);
        $hPosting = (new Posting($amount, $comment))
            ->setFrom($hFrom)
            ->setTo($hTo);
        return $this->hStorage->transferRuble($hPosting);
    }

    /**
     * @param int $firm
     * @param int $user
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function transferFirmUserRuble(int $firm, int $user, float $amount, string $comment): bool
    {
        $hFrom = $this->hStorage->getAccount($firm, Account::TYPE_FIRM);
        $hTo = $this->hStorage->getAccount($user, Account::TYPE_USER);
        $hPosting = (new Posting($amount, $comment))
            ->setFrom($hFrom)
            ->setTo($hTo);
        return $this->hStorage->transferRuble($hPosting);
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