<?php declare(strict_types=1);

namespace Advecs\App\Controller\Error;

use Advecs\App\Controller\Controller;
use Advecs\App\HTTP\Response;

class ErrorController extends Controller
{
    /** @return Response */
    public function getResponse(): Response
    {
        return parent::getResponse()
            ->setData('ошибка');
    }
}