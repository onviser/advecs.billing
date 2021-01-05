<?php declare(strict_types=1);

namespace Advecs\Billing\Storage;

use Advecs\Billing\Account\Account;
use Advecs\Billing\Posting\Posting;
use Advecs\Billing\PSCB\PSCBNotify;
use Advecs\Billing\PSCB\PSCBPayment;
use Advecs\Billing\Search\Search;
use Advecs\Billing\Search\SearchAccount;
use Advecs\Billing\Search\SearchPayment;

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
     * @param int $account
     * @return int
     */
    public function getIdUser(int $account): int;

    /**
     * @param int $account
     * @return int
     */
    public function getIdFirm(int $account): int;

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

    /**
     * @param PSCBPayment $hPayment
     * @return bool
     */
    public function updatePSCBPayment(PSCBPayment $hPayment): bool;

    /**
     * @param PSCBNotify $hPSCBNotify
     * @return bool
     */
    public function addPSCBNotify(PSCBNotify $hPSCBNotify): bool;

    /**
     * @param SearchAccount $hSearch
     * @return Account[]
     */
    public function searchAccount(SearchAccount $hSearch): array;

    /**
     * @param SearchPayment $hSearch
     * @return PSCBPayment[]
     */
    public function searchPayment(SearchPayment $hSearch): array;

    /**
     * @param int $id
     * @return PSCBPayment|null
     */
    public function searchPaymentById(int $id): ?PSCBPayment;
}