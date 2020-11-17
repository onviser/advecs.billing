<?php declare(strict_types=1);

namespace Advecs\App\Router;

use Advecs\App\Controller\DefaultController;
use Advecs\App\Controller\PSCB\PaymentPSCBController;

/**
 * Class RouterBuilder
 * @package Advecs\App\Router
 */
class RouterBuilder
{
    /**
     * @param $URI
     * @return Router
     */
    public static function build($URI): Router
    {
        $hRouter = new Router($URI);
        $hRouter->add('/', new Path(DefaultController::class));
        $hRouter->add('/pscb.html', new Path(PaymentPSCBController::class));
        return $hRouter;
    }
}