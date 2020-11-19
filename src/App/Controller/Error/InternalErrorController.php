<?php declare(strict_types=1);

namespace Advecs\App\Controller\Error;

use Advecs\App\HTTP\Response;

class InternalErrorController extends ErrorController
{
    /** @return Response */
    public function getResponse(): Response
    {
        return parent::getResponse()
            ->addHeader('HTTP/1.1 503 Service Temporarily Unavailable')
            ->addHeader('Status', '503 Service Temporarily Unavailable')
            ->addHeader('Retry-After', '600')
            ->addHeader('Cache-Control', 'no-cache')
            ->setData('Ошибка приложения');
    }
}