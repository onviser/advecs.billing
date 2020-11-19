<?php declare(strict_types=1);

namespace Advecs\App\Controller\PSCB;

use Advecs\App\Controller\Controller;
use Advecs\App\HTTP\Response;
use Advecs\Billing\BillingInterface;
use Advecs\Billing\PSCB\PSCBPayment;
use Advecs\Template\Page\PSCB\DemoPSCBPageTemplate;

/**
 * Class DemoPSCBController
 * @package Advecs\App\Controller\PSCB
 */
class DemoPSCBController extends Controller
{
    /** @var BillingInterface */
    protected $hBilling;

    /**
     * DemoPSCBController constructor.
     * @param BillingInterface $hBilling
     */
    public function __construct(BillingInterface $hBilling)
    {
        $this->hBilling = $hBilling;
    }

    /** @return Response */
    public function getResponse(): Response
    {
        // обработка платежа
        if ($this->hRequest->isExists('payment')) {
            $account = 1;
            $amount = 1.1;
            $comment = 'тестовый платеж для аккаунта ' . $account;
            $hPSCBPayment = (new PSCBPayment($account, $amount))
                ->setType(PSCBPayment::TYPE_CARD)
                ->setStatus(PSCBPayment::STATUS_NEW)
                ->setComment($comment);
            $this->hBilling->addPSCBPayment($hPSCBPayment);
        }

        $hTemplatePage = new DemoPSCBPageTemplate();
        return parent::getResponse()->setData(
            $this->getPageTemplate($hTemplatePage)->getData());
    }
}