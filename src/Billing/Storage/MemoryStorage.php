<?php declare(strict_types=1);

namespace Advecs\Billing\Storage;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Account\FactoryAccount;
use Advecs\Billing\Posting\Posting;
use Advecs\Billing\PSCB\PSCBNotify;
use Advecs\Billing\PSCB\PSCBPayment;
use Advecs\Billing\Search\Search;
use Advecs\Billing\Search\SearchAccount;
use Advecs\Billing\Search\SearchPayment;

/**
 * Class MemoryStorage
 * @package Advecs\Billing\Storage
 */
class MemoryStorage implements StorageInterface
{
    /** @var Account[][] */
    protected $account = [];

    /** @var Posting[] */
    protected $postingRuble = [];

    /** @var Posting[] */
    protected $postingBonus = [];

    /** @var PSCBPayment[] */
    protected $payment = [];

    /** @var PSCBNotify[] */
    protected $notify = [];

    /**
     * @param int $id
     * @param int $type
     * @return Account
     */
    public function getAccount(int $id, int $type = Account::TYPE_USER): Account
    {
        if (array_key_exists($type, $this->account)) {
            if (array_key_exists($id, $this->account[$type])) {
                return $this->account[$type][$id];
            }
        }
        $this->account[$type][$id] = FactoryAccount::getInstance($type, $id);
        return $this->account[$type][$id];
    }

    /**
     * @param int $account
     * @return int
     */
    public function getIdUser(int $account): int
    {
        if (array_key_exists(Account::TYPE_USER, $this->account)) {
            foreach ($this->account[Account::TYPE_USER] as $id => $hAccount) {
                if ($account === $id) {
                    return $hAccount->getIdExternal();
                }
            }
        }
        return 0;
    }

    /**
     * @param int $account
     * @return int
     */
    public function getIdFirm(int $account): int
    {
        if (array_key_exists(Account::TYPE_FIRM, $this->account)) {
            foreach ($this->account[Account::TYPE_FIRM] as $id => $hAccount) {
                if ($account === $id) {
                    return $hAccount->getIdExternal();
                }
            }
        }
        return 0;
    }

    /**
     * @param int $account
     * @return int
     */
    public function getIdSystem(int $account): int
    {
        if (array_key_exists(Account::TYPE_SYSTEM, $this->account)) {
            foreach ($this->account[Account::TYPE_SYSTEM] as $id => $hAccount) {
                if ($account === $id) {
                    return $hAccount->getIdExternal();
                }
            }
        }
        return 0;
    }

    /**
     * @param Posting $hPostingCredit
     * @return bool
     */
    public function addRuble(Posting $hPostingCredit): bool
    {
        $hAccount = $hPostingCredit->getTo();
        $hAccount->changeBalance($hPostingCredit->getAmount());
        $this->postingRuble[$hAccount->getType()][$hAccount->getId()][] = $hPostingCredit;
        return true;
    }

    /**
     * @param Posting $hPostingCredit
     * @return bool
     */
    public function addBonus(Posting $hPostingCredit): bool
    {
        $hAccount = $hPostingCredit->getTo();
        $hAccount->changeBalanceBonus($hPostingCredit->getAmount());
        $this->postingBonus[$hAccount->getType()][$hAccount->getId()][] = $hPostingCredit;
        return true;
    }

    /**
     * @param Posting $hPostingCredit
     * @return bool
     */
    public function transferRuble(Posting $hPostingCredit): bool
    {
        // зачисление
        $hTo = $hPostingCredit->getTo();
        $hTo->changeBalance($hPostingCredit->getAmount());
        $this->postingRuble[$hTo->getType()][$hTo->getId()][] = $hPostingCredit;

        // списание
        $hPostingDebit = new Posting(-1 * $hPostingCredit->getAmount(), $hPostingCredit->getComment());
        $hFrom = $hPostingCredit->getFrom();
        $hFrom->changeBalance($hPostingDebit->getAmount());
        $this->postingRuble[$hFrom->getType()][$hFrom->getId()][] = $hPostingDebit;

        return true;
    }

    /**
     * @param Posting $hPostingCredit
     * @return bool
     */
    public function transferBonus(Posting $hPostingCredit): bool
    {
        // зачисление
        $hTo = $hPostingCredit->getTo();
        $hTo->changeBalanceBonus($hPostingCredit->getAmount());
        $this->postingBonus[$hTo->getType()][$hTo->getId()][] = $hPostingCredit;

        // списание
        $hPostingDebit = new Posting(-1 * $hPostingCredit->getAmount(), $hPostingCredit->getComment());
        $hFrom = $hPostingCredit->getFrom();
        $hFrom->changeBalanceBonus($hPostingDebit->getAmount());
        $this->postingBonus[$hFrom->getType()][$hFrom->getId()][] = $hPostingDebit;

        return true;
    }

    /**
     * @param Search $hSearch
     * @return Posting[]
     */
    public function getPosting(Search $hSearch): array
    {
        $total = 0;
        $result = [];
        foreach ($this->postingRuble as $accountType => $items) {
            if ($hSearch->getAccountType() > 0) {
                if ($hSearch->getAccountType() != $accountType) {
                    continue;
                }
            }
            /**@var Posting $hPosting */
            foreach ($items as $account => $subItems) {
                foreach ($subItems as $hPosting) {
                    if ($hSearch->getAccount() > 0) {
                        if ($hSearch->getAccount() != $account) {
                            continue;
                        }
                    }
                    if ($hSearch->getAmountFrom() > 0) {
                        if ($hSearch->getAmountFrom() > abs($hPosting->getAmount())) {
                            continue;
                        }
                    }
                    if ($hSearch->getAmountTo() > 0) {
                        if ($hSearch->getAmountTo() < abs($hPosting->getAmount())) {
                            continue;
                        }
                    }
                    if ($hSearch->getTimeFrom() > 0) {
                        if ($hSearch->getTimeFrom() > $hPosting->getTime()) {
                            continue;
                        }
                    }
                    if ($hSearch->getTimeTo() > 0) {
                        if ($hSearch->getTimeTo() < $hPosting->getTime()) {
                            continue;
                        }
                    }
                    if ($hSearch->getComment() != '') {
                        if (strpos($hPosting->getComment(), $hSearch->getComment()) === false) {
                            continue;
                        }
                    }
                    $total++;
                    $result[] = $hPosting;
                }
            }
        }

        if ($hSearch->getLimit() > 0) {
            $result = array_slice(
                $result,
                $hSearch->getOffset(),
                $hSearch->getLimit()
            );
        }

        $hSearch->setTotal($total);

        return $result;
    }

    /**
     * @param Search $hSearch
     * @return Posting[]
     */
    public function getPostingBonus(Search $hSearch): array
    {
        return [];
    }

    /**
     * @param PSCBPayment $hPSCBPayment
     * @return bool
     */
    public function addPSCBPayment(PSCBPayment $hPSCBPayment): bool
    {
        $this->payment[] = $hPSCBPayment;
        return true;
    }

    /**
     * @param PSCBPayment $hPayment
     * @return bool
     */
    public function updatePSCBPayment(PSCBPayment $hPayment): bool
    {
        foreach ($this->payment as $index => $hItem) {
            if ($hItem->getId() === $hPayment->getId()) {
                $this->payment[$index] = $hPayment;
                return true;
            }
        }
        return false;
    }

    /**
     * @param PSCBNotify $hPSCBNotify
     * @return bool
     */
    public function addPSCBNotify(PSCBNotify $hPSCBNotify): bool
    {
        $this->notify[] = $hPSCBNotify;
        return true;
    }

    /**
     * @param Account $hAccount
     * @return bool
     */
    public function reCount(Account $hAccount): bool
    {
        $balance = 0;
        if (isset($this->postingRuble[$hAccount->getType()])) {
            if (isset($this->postingRuble[$hAccount->getType()][$hAccount->getId()])) {
                $postings = $this->postingRuble[$hAccount->getType()][$hAccount->getId()];
                foreach ($postings as $hPosting) {
                    /** @var Posting $hPosting */
                    $balance += $hPosting->getAmount();
                }
            }
        }
        $hAccount->setBalance($balance);

        $balanceBonus = 0;
        if (isset($this->postingBonus[$hAccount->getType()])) {
            if (isset($this->postingBonus[$hAccount->getType()][$hAccount->getId()])) {
                $postings = $this->postingBonus[$hAccount->getType()][$hAccount->getId()];
                foreach ($postings as $hPosting) {
                    /** @var Posting $hPosting */
                    $balanceBonus += $hPosting->getAmount();
                }
            }
        }
        $hAccount->setBalanceBonus($balanceBonus);

        return true;
    }

    /**
     * @param SearchAccount $hSearch
     * @return Account[]
     */
    public function searchAccount(SearchAccount $hSearch): array
    {
        return $this->account;
    }

    /**
     * @param SearchPayment $hSearch
     * @return PSCBPayment[]
     */
    public function searchPayment(SearchPayment $hSearch): array
    {
        return $this->payment;
    }

    /**
     * @param int $id
     * @return PSCBPayment|null
     */
    public function searchPaymentById(int $id): ?PSCBPayment
    {
        foreach ($this->payment as $hPayment) {
            if ($hPayment->getId() === $id) {
                return $hPayment;
            }
        }
        return null;
    }
}