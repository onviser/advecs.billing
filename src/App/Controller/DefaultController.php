<?php declare(strict_types=1);

namespace Advecs\App\Controller;

use Advecs\App\HTTP\Response;

/**
 * Class DefaultController
 * @package Advecs\App\Controller
 */
class DefaultController extends Controller
{
    /** @return Response */
    public function getResponse(): Response
    {
        return parent::getResponse()
            ->setData('default');
    }
}