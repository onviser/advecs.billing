<?php declare(strict_types=1);

namespace Advecs\App\Controller\PSCB;

use Advecs\App\Controller\Controller;
use Advecs\App\HTTP\Response;
use Advecs\Template\Page\PSCB\DemoPSCBPageTemplate;

class DemoPSCBController extends Controller
{
    /** @return Response */
    public function getResponse(): Response
    {
        $hTemplatePage = new DemoPSCBPageTemplate();
        return parent::getResponse()->setData(
            $this->getPageTemplate($hTemplatePage)->getData());
    }
}