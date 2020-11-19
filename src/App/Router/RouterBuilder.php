<?php declare(strict_types=1);

namespace Advecs\App\Router;

use Advecs\App\Controller\DefaultController;
use Advecs\App\Controller\PSCB\DemoPSCBController;
use Advecs\App\Controller\PSCB\FailPSCBController;
use Advecs\App\Controller\PSCB\PaymentPSCBController;
use Advecs\App\Controller\PSCB\SuccessPSCBController;

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
        $hRouter->add('/pscb/', new Path(PaymentPSCBController::class));
        $hRouter->add('/pscb/success.html', new Path(SuccessPSCBController::class));
        $hRouter->add('/pscb/fail.html', new Path(FailPSCBController::class));
        $hRouter->add('/pscb/demo.html', new Path(DemoPSCBController::class));
        return $hRouter;
    }
}