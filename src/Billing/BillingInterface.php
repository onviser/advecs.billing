<?php declare();

namespace Advecs\Billing;

interface BillingInterface
{
    /**
     * @param $intIdAccount
     * @return float
     */
    public function getBalance($intIdAccount);

    /**
     * @param $intIdAccount
     * @param $flAmount
     * @param $strComment
     * @return bool
     */
    public function moneyIn($intIdAccount, $flAmount, $strComment);

    /**
     * @param $intIdAccount
     * @param $flAmount
     * @param $strComment
     * @return bool
     */
    public function moneyOut($intIdAccount, $flAmount, $strComment);

    /**
     * @param $intIdAccount
     * @param $intIdAccountTo
     * @param $flAmount
     * @param $strComment
     * @return bool
     */
    public function moneyTransfer($intIdAccount, $intIdAccountTo, $flAmount, $strComment);
}