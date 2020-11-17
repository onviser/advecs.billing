<?php declare(strict_types=1);

namespace Advecs\App\Controller\PSCB;

use Advecs\App\Controller\Controller;
use Advecs\App\HTTP\Response;

class PaymentPSCBController extends Controller
{
    /** @return Response */
    public function getResponse(): Response
    {
        return parent::getResponse()
            ->setData('ПСКБ, платежи');
    }
}