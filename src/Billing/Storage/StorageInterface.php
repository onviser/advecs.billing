<?php declare(strict_types=1);

namespace Advecs\Billing\Storage;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Posting\Posting;
use Advecs\Billing\PSCB\PSCBPayment;
use Advecs\Billing\Search\Search;

/**
 * Interface StorageInterface
 * @package Advecs\Billing\Storage
 */
interface StorageInterface
{
    /**
     * @param int $id
     * @param int $type
     * @return Account
     */
    public function getAccount(int $id, int $type = Account::TYPE_USER): Account;

    /**
     * @param Posting $hPostingCredit
     * @return bool
     */
    public function addRuble(Posting $hPostingCredit): bool;

    /**
     * @param Posting $hPostingCredit
     * @return bool
     */
    public function addBonus(Posting $hPostingCredit): bool;

    /**
     * @param Posting $hPostingCredit
     * @return bool
     */
    public function transferRuble(Posting $hPostingCredit): bool;

    /**
     * @param Search $hSearch
     * @return Posting[]
     */
    public function getPosting(Search $hSearch): array;

    /**
     * @param Search $hSearch
     * @return Posting[]
     */
    public function getPostingBonus(Search $hSearch): array;

    /**
     * @param Account $hAccount
     * @return bool
     */
    public function reCount(Account $hAccount): bool;

    /**
     * @param PSCBPayment $hPayment
     * @return bool
     */
    public function addPSCBPayment(PSCBPayment $hPayment): bool;
}