<?php declare(strict_types=1);

namespace Advecs\App\Controller\PSCB;

use Advecs\App\Controller\Controller;
use Advecs\App\HTTP\Response;
use Advecs\App\Observer\Dispatcher;
use Advecs\App\Observer\Event\PSCB\PSCBPaymentEvent;
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

    /** @var Dispatcher */
    protected $hDispatcher;

    /**
     * DemoPSCBController constructor.
     * @param BillingInterface $hBilling
     * @param Dispatcher $hDispatcher
     */
    public function __construct(BillingInterface $hBilling, Dispatcher $hDispatcher)
    {
        $this->hBilling = $hBilling;
        $this->hDispatcher = $hDispatcher;
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

            $this->hDispatcher->dispatch(PSCBPaymentEvent::EVENT_ADD, new PSCBPaymentEvent($hPSCBPayment));
            $this->hDispatcher->dispatch(PSCBPaymentEvent::EVENT_RECEIVE, new PSCBPaymentEvent($hPSCBPayment));
        }

        $hTemplatePage = new DemoPSCBPageTemplate();
        return parent::getResponse()->setData(
            $this->getPageTemplate($hTemplatePage)->getData());
    }
}