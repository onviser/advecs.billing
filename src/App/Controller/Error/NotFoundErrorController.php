<?php declare(strict_types=1);

namespace Advecs\App\Controller\Error;

use Advecs\App\HTTP\Response;

class NotFoundErrorController extends ErrorController
{
    /** @return Response */
    public function getResponse(): Response
    {
        return parent::getResponse()
            ->addHeader('HTTP/1.0 404 Not Found')
            ->setData('Страница не найдена');
    }
}