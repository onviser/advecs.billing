<?php declare(strict_types=1);

namespace Advecs\Billing;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Exception\NotEnoughException;
use Advecs\Billing\Posting\Posting;
use Advecs\Billing\PSCB\PSCBNotify;
use Advecs\Billing\PSCB\PSCBOrder;
use Advecs\Billing\PSCB\PSCBPayment;
use Advecs\Billing\Search\Search;
use Advecs\Billing\Search\SearchAccount;
use Advecs\Billing\Search\SearchPayment;
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
        $hAccount = $this->hStorage->getAccount($id, Account::TYPE_USER);
        if ($hAccount->isExist()) {
            $hAccount->setIdExternal($id);
        }
        return $hAccount;
    }

    /**
     * возвращает id пользоватетеля по аккаунту
     * @param int $account
     * @return int
     */
    public function getIdUser(int $account): int
    {
        return $this->hStorage->getIdUser($account);
    }

    /**
     * Аккаунт фирмы в биллинге
     * @return Account|null
     * @var int $id
     */
    public function getAccountFirm(int $id): ?Account
    {
        $hAccount = $this->hStorage->getAccount($id, Account::TYPE_FIRM);
        if ($hAccount->isExist()) {
            $hAccount->setIdExternal($id);
        }
        return $hAccount;
    }

    /**
     * возвращает id фирмы по аккаунту
     * @param int $account
     * @return int
     */
    public function getIdFirm(int $account): int
    {
        return $this->hStorage->getIdFirm($account);
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
     * Баланс фирмы
     * @param int $id
     * @return float
     */
    public function getFirmBalanceBonus(int $id): float
    {
        $hFirm = $this->getAccountFirm($id);
        return $hFirm ? $hFirm->getBalanceBonus() : 0.0;
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
     * Пополнение бонусного счета пользователя
     * @param int $id
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addFirmBonus(int $id, float $amount, string $comment = 'зачисление бонусов'): bool
    {
        $hUser = $this->getAccountFirm($id);
        $hPosting = (new Posting($amount, $comment))
            ->setTo($hUser);
        return $this->hStorage->addBonus($hPosting);
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
     * Перевод бонусов от пользователя фирме
     * @param int $user
     * @param int $firm
     * @param float $amount
     * @param string $comment
     * @return bool
     * @throws Exception\BillingException
     */
    public function transferUserFirmBonus(int $user, int $firm, float $amount, string $comment): bool
    {
        $hFrom = $this->getAccountUser($user);
        $hTo = $this->getAccountFirm($firm);
        $hPosting = (new Posting($amount, $comment))
            ->setFrom($hFrom)
            ->setTo($hTo);

        // недостаточно бонусов
        if ($hFrom->getBalanceBonus() < $amount) {
            throw (new NotEnoughException('недостаточно бонусов'))
                ->setAccount($hFrom)
                ->setPosting($hPosting);
        }

        return $this->hStorage->transferBonus($hPosting);
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
     * @param PSCBNotify $hNotify
     * @return bool
     */
    public function addPSCBNotify(PSCBNotify $hNotify): bool
    {
        return $this->hStorage->addPSCBNotify($hNotify);
    }

    /**
     * @param PSCBNotify $hPSCBNotify
     * @return PSCBOrder[]
     */
    public function processingPSCBNotify(PSCBNotify $hPSCBNotify): array
    {
        if (!$this->addPSCBNotify($hPSCBNotify)) {
            return [];
        }

        $orders = $hPSCBNotify->getOrders();
        foreach ($orders as $hOrder) {

            // по умолчанию считаем, что платеж принят
            $hOrder->setAction(PSCBOrder::STATUS_CONFIRM);

            $hPayment = $this->hStorage->searchPaymentById($hOrder->getId());
            if (!$hPayment) {
                $hOrder
                    ->setAction(PSCBOrder::STATUS_REJECT)
                    ->setError('не удалось найти платеж ' . $hOrder->getId());
                continue;
            }

            // платеж просрочен
            if ($hOrder->getState() === 'exp') {
                $hOrder->setError('платеж просрочен, состояние: ' . $hOrder->getState());
                continue;
            }

            // платеж еще не обработан
            if ($hOrder->getState() !== 'end') {
                $hOrder->setError('платеж не завершен, состояние: ' . $hOrder->getState());
                continue;
            }

            // платеж уже обработан
            if ($hPayment->getStatus() === PSCBPayment::STATUS_END) {
                $hOrder
                    ->setAction(PSCBOrder::STATUS_REJECT)
                    ->setError('платеж уже обработан');
                continue;
            }

            // определяем пользователя по номеру счета
            $user = $this->getIdUser($hOrder->getAccount());
            if ($user === 0) {
                $hOrder
                    ->setAction(PSCBOrder::STATUS_REJECT)
                    ->setError('не удалось найти пользователя по номеру счета ' . $hOrder->getAccount());
                continue;
            }

            $hPayment
                ->setStatus(PSCBPayment::STATUS_END)
                ->setType($hOrder->getMethod())
                ->setJSON($hOrder->getJSON());

            // ошибка пополнения счета
            if (!$this->hStorage->updatePSCBPayment($hPayment)) {
                $hOrder
                    ->setAction(PSCBOrder::STATUS_REJECT)
                    ->setError('не удалось обновить платеж ' . $hPayment->getId());
                continue;
            }

            $comment = 'пополнение счета, ПСКБ, платеж ' . $hOrder->getId();
            if (!$this->addUserRuble($user, $hPayment->getAmount(), $comment)) {
                $hOrder
                    ->setAction(PSCBOrder::STATUS_REJECT)
                    ->setError('не удалось пополниеть счет ' . $hOrder->getAccount());
                continue;
            }

            $hOrder->setAction(PSCBOrder::STATUS_CONFIRM);
        }

        return $orders;
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

    /**
     * @param SearchPayment $hSearch
     * @return PSCBPayment[]
     */
    public function searchPayment(SearchPayment $hSearch): array
    {
        return $this->hStorage->searchPayment($hSearch);
    }

    /**
     * @param Account $hAccount
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addRuble(Account $hAccount, float $amount, string $comment = 'пополнение счета'): bool
    {
        $hPosting = (new Posting($amount, $comment))
            ->setTo($hAccount);
        return $this->hStorage->addRuble($hPosting);
    }

    /**
     * @param Account $hAccount
     * @param float $amount
     * @param string $comment
     * @return bool
     */
    public function addBonus(Account $hAccount, float $amount, string $comment = 'зачисление бонусов'): bool
    {
        $hPosting = (new Posting($amount, $comment))
            ->setTo($hAccount);
        return $this->hStorage->addBonus($hPosting);
    }

    /**
     * @param Account $from
     * @param Account $to
     * @param float $amount
     * @param string $comment
     * @return bool
     * @throws Exception\BillingException
     */
    public function transferRuble(Account $from, Account $to, float $amount, string $comment): bool
    {
        $hPosting = (new Posting($amount, $comment))
            ->setFrom($from)
            ->setTo($to);

        // недостаточно средств
        if ($from->getBalance() < $amount) {
            throw (new NotEnoughException('недостаточно средств'))
                ->setAccount($from)
                ->setPosting($hPosting);
        }

        return $this->hStorage->transferRuble($hPosting);
    }

    /**
     * @param Account $from
     * @param Account $to
     * @param float $amount
     * @param string $comment
     * @return bool
     * @throws Exception\BillingException
     */
    public function transferBonus(Account $from, Account $to, float $amount, string $comment): bool
    {
        $hPosting = (new Posting($amount, $comment))
            ->setFrom($from)
            ->setTo($to);

        // недостаточно бонусов
        if ($from->getBalanceBonus() < $amount) {
            throw (new NotEnoughException('недостаточно бонусов'))
                ->setAccount($from)
                ->setPosting($hPosting);
        }

        return $this->hStorage->transferBonus($hPosting);
    }
}