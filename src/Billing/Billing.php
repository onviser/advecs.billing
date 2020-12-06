<?php declare(strict_types=1);

namespace Advecs\Billing;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Exception\NotEnoughException;
use Advecs\Billing\Posting\Posting;
use Advecs\Billing\PSCB\PSCBPayment;
use Advecs\Billing\Search\Search;
use Advecs\Billing\Search\SearchAccount;
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
     * Аккаунт пользователя в биллинге
     * @return Account|null
     * @var int $id
     */
    public function getAccountUser(int $id): ?Account
    {
        return $this->hStorage->getAccount($id, Account::TYPE_USER);
    }

    /**
     * Аккаунт фирмы в биллинге
     * @return Account|null
     * @var int $id
     */
    public function getAccountFirm(int $id): ?Account
    {
        return $this->hStorage->getAccount($id, Account::TYPE_FIRM);
    }

    /**
     * Баланс пользователя в рублях
     * @param int $id
     * @return float
     */
    public function getUserBalanceRuble(int $id): float
    {
        $hUser = $this->getAccountUser($id);
        return $hUser ? $hUser->getBalance() : 0.0;
    }

    /**
     * Баланс пользователя в бонусах
     * @param int $id
     * @return float
     */
    public function getUserBalanceBonus(int $id): float
    {
        $hUser = $this->getAccountUser($id);
        return $hUser ? $hUser->getBalanceBonus() : 0.0;
    }

    /**
     * Пополнение рублевого счета пользователя
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addUserRuble(int $id, float $amount, string $comment = 'пополнение счета'): bool
    {
        $hUser = $this->getAccountUser($id);
        $hPosting = (new Posting($amount, $comment))
            ->setTo($hUser);
        return $this->hStorage->addRuble($hPosting);
    }

    /**
     * Пополнение бонусного счета пользователя
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addUserBonus(int $id, float $amount, string $comment = 'зачисление бонусов'): bool
    {
        $hUser = $this->getAccountUser($id);
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
     * @throws Exception\BillingException
     */
    public function transferUserRuble(int $from, int $to, float $amount, string $comment): bool
    {
        $hFrom = $this->getAccountUser($from);
        $hTo = $this->getAccountUser($to);
        $hPosting = (new Posting($amount, $comment))
            ->setFrom($hFrom)
            ->setTo($hTo);

        // недостаточно средств
        if ($hFrom->getBalance() < $amount) {
            throw (new NotEnoughException('недостаточно средств'))
                ->setAccount($hFrom)
                ->setPosting($hPosting);
        }

        return $this->hStorage->transferRuble($hPosting);
    }

    /**
     * Баланс фирмы
     * @param int $id
     * @return float
     */
    public function getFirmBalanceRuble(int $id): float
    {
        $hFirm = $this->getAccountFirm($id);
        return $hFirm ? $hFirm->getBalance() : 0.0;
    }

    /**
     * Пополнение счета фирмы
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addFirmRuble(int $id, float $amount, string $comment = 'пополнение счета фирмы'): bool
    {
        $hFirm = $this->getAccountFirm($id);
        $hPosting = (new Posting($amount, $comment))
            ->setTo($hFirm);
        return $this->hStorage->addRuble($hPosting);
    }

    /**
     * Перевод средств от фирмы к фирме
     * @param int $from
     * @param int $to
     * @param float $amount
     * @param string $comment
     * @return bool
     * @throws Exception\BillingException
     */
    public function transferFirmRuble(int $from, int $to, float $amount, string $comment): bool
    {
        $hFrom = $this->getAccountFirm($from);
        $hTo = $this->getAccountFirm($to);
        $hPosting = (new Posting($amount, $comment))
            ->setFrom($hFrom)
            ->setTo($hTo);

        // недостаточно средств
        if ($hFrom->getBalance() < $amount) {
            throw (new NotEnoughException('недостаточно средств'))
                ->setAccount($hFrom)
                ->setPosting($hPosting);
        }

        return $this->hStorage->transferRuble($hPosting);
    }

    /**
     * Перевод средств от пользователя фирме
     * @param int $user
     * @param int $firm
     * @param float $amount
     * @param string $comment
     * @return bool
     * @throws Exception\BillingException
     */
    public function transferUserFirmRuble(int $user, int $firm, float $amount, string $comment): bool
    {
        $hFrom = $this->getAccountUser($user);
        $hTo = $this->getAccountFirm($firm);
        $hPosting = (new Posting($amount, $comment))
            ->setFrom($hFrom)
            ->setTo($hTo);

        // недостаточно средств
        if ($hFrom->getBalance() < $amount) {
            throw (new NotEnoughException('недостаточно средств'))
                ->setAccount($hFrom)
                ->setPosting($hPosting);
        }

        return $this->hStorage->transferRuble($hPosting);
    }

    /**
     * Перевод средств от фирмы пользователю
     * @param int $firm
     * @param int $user
     * @param float $amount
     * @param string $comment
     * @return bool
     * @throws Exception\BillingException
     */
    public function transferFirmUserRuble(int $firm, int $user, float $amount, string $comment): bool
    {
        $hFrom = $this->getAccountFirm($firm);
        $hTo = $this->getAccountUser($user);
        $hPosting = (new Posting($amount, $comment))
            ->setFrom($hFrom)
            ->setTo($hTo);

        // недостаточно средств
        if ($hFrom->getBalance() < $amount) {
            throw (new NotEnoughException('недостаточно средств'))
                ->setAccount($hFrom)
                ->setPosting($hPosting);
        }

        return $this->hStorage->transferRuble($hPosting);
    }

    /**
     * Список проводок
     * @param Search $hSearch
     * @return Posting[]
     */
    public function getPosting(Search $hSearch): array
    {
        return $this->hStorage->getPosting($hSearch);
    }

    /**
     * Список проводок по бонусному счету
     * @param Search $hSearch
     * @return Posting[]
     */
    public function getPostingBonus(Search $hSearch): array
    {
        return $this->hStorage->getPostingBonus($hSearch);
    }

    /**
     * Пересчет баланса (рубли, бонусы) пользователя
     * @param int $id
     * @return bool
     */
    public function reCountUser(int $id): bool
    {
        $hAccount = $this->getAccountUser($id);
        $this->hStorage->reCount($hAccount);
        return true;
    }

    /**
     * Пересчет баланса (рубли, бонусы) фирмы
     * @param int $id
     * @return bool
     */
    public function reCountFirm(int $id): bool
    {
        $hAccount = $this->getAccountFirm($id);
        $this->hStorage->reCount($hAccount);
        return true;
    }

    /**
     * @param PSCBPayment $hPayment
     * @return bool
     */
    public function addPSCBPayment(PSCBPayment $hPayment): bool
    {
        return $this->hStorage->addPSCBPayment($hPayment);
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

    /**
     * @param SearchAccount $hSearch
     * @return Account[]
     */
    public function searchAccount(SearchAccount $hSearch): array
    {
        return $this->hStorage->searchAccount($hSearch);
    }
}