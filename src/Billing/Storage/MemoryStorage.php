<?php declare(strict_types=1);

namespace Advecs\Billing\Storage;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Account\Firm;
use Advecs\Billing\Account\User;
use Advecs\Billing\Posting\Posting;
use Advecs\Billing\Search\Search;

class MemoryStorage implements StorageInterface
{
    /** @var Account[][] */
    protected $account = [];

    /** @var Posting[] */
    protected $postingRuble = [];

    /** @var Posting[] */
    protected $postingBonus = [];

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
        $this->account[$type][$id] = ($type === Account::TYPE_FIRM) ? new Firm($id) : new User($id);
        return $this->account[$type][$id];
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
        /** @var User $hUser */
        $hUser = $hPostingCredit->getTo();
        $hUser->changeBalanceBonus($hPostingCredit->getAmount());
        $this->postingBonus[$hUser->getType()][$hUser->getId()][] = $hPostingCredit;
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
     * @param Search $hSearch
     * @return Posting[]
     */
    public function getPosting(Search $hSearch): array
    {
        $result = [];
        foreach ($this->postingRuble as $accountType => $items) {
            if ($hSearch->getAccountType() > 0) {
                if ($hSearch->getAccountType() != $accountType) {
                    continue;
                }
            }
            /**@var Posting $hPosting */
            foreach ($items as $account => $hPosting) {
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
                $result[] = $hPosting;
            }
        }
        return $result;
    }
}