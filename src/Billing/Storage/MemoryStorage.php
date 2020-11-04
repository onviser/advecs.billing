<?php declare(strict_types=1);

namespace Advecs\Billing\Storage;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Account\Firm;
use Advecs\Billing\Account\User;
use Advecs\Billing\Posting\Posting;

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
     * @param Account $hAccount
     * @param Posting $hPosting
     * @return bool
     */
    public function addRuble(Account $hAccount, Posting $hPosting): bool
    {
        $hAccount->changeBalance($hPosting);
        $this->postingRuble[$hAccount->getType()][$hAccount->getId()][] = $hPosting;
        return true;
    }

    /**
     * @param User $hUser
     * @param Posting $hPosting
     * @return bool
     */
    public function addBonus(User $hUser, Posting $hPosting): bool
    {
        $hUser->changeBalanceBonus($hPosting);
        $this->postingBonus[$hUser->getType()][$hUser->getId()][] = $hPosting;
        return true;
    }
}