<?php declare(strict_types=1);

namespace Advecs\App\Controller;

use Advecs\App\HTTP\Response;
use Advecs\Template\Page\DefaultPageTemplate;

/**
 * Class DefaultController
 * @package Advecs\App\Controller
 */
class DefaultController extends Controller
{
    /** @return Response */
    public function getResponse(): Response
    {
        $hTemplatePage = new DefaultPageTemplate();
        return parent::getResponse()->setData(
            $this->getPageTemplate($hTemplatePage)->getData());
    }
}