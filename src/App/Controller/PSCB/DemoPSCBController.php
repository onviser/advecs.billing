<?php declare(strict_types=1);

namespace Advecs\App\Controller\PSCB;

use Advecs\App\Controller\Controller;
use Advecs\App\HTTP\Response;
use Advecs\App\Observer\Dispatcher;
use Advecs\App\Observer\Event\PSCB\PSCBPaymentEvent;
use Advecs\Billing\BillingInterface;
use Advecs\Billing\PSCB\PSCBPayment;
use Advecs\Template\Page\PSCB\DemoPaymentPSCBPageTemplate;
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

            $url = $this->hConfig->get('app.protocol') . '://';
            $url .= $this->hConfig->get('app.domain');

            $urlPSCB = $this->hConfig->get('pscb.url') . 'pay/';
            $marketPlace = $this->hConfig->get('pscb.marketPlace');
            $secretKey = $this->hConfig->get('pscb.secretKey');
            $message = [
                'amount'          => $amount,
                'orderId'         => $hPSCBPayment->getId(),
                'details'         => $comment,
                //'paymentMethod'   => 'ac',
                'customerAccount' => $account,
                'successUrl'      => $url . '/pscb/success.html',
                'failUrl'         => $url . '/pscb/fail.html',
                'nonce'           => md5(rand(1, 1000000) . time() . $hPSCBPayment->getId())
            ];
            $messageText = json_encode($message);

            $hTemplatePage = new DemoPaymentPSCBPageTemplate(
                $urlPSCB,
                $marketPlace,
                base64_encode($messageText),
                hash('sha256', $messageText . $secretKey)
            );
            return parent::getResponse()->setData(
                $this->getPageTemplate($hTemplatePage)->getData());
        }

        $hTemplatePage = new DemoPSCBPageTemplate();
        return parent::getResponse()->setData(
            $this->getPageTemplate($hTemplatePage)->getData());
    }
}