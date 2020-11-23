<?php declare(strict_types=1);

namespace Advecs\App\ServiceLocator;

use Advecs\App\Config\Config;
use Advecs\App\Debug\Debug;
use Advecs\App\Observer\Dispatcher;
use Advecs\App\Observer\Subscriber\PSCBSubscriber;
use Advecs\Billing\Billing;
use Advecs\Billing\BillingInterface;
use Advecs\Billing\Storage\MySQLStorage;

/**
 * Class ServiceLocatorBuilder
 * @package Advecs\App\ServiceLocator
 */
class ServiceLocatorBuilder
{
    /** @return ServiceLocator */
    public static function build(): ServiceLocator
    {
        $hSL = new ServiceLocator();

        $hSL->add(Debug::class, function () use ($hSL) {
            $isUse = boolval($hSL->getConfig()->get('app.debug'));
            if ($isUse) {
                ini_set('display_errors', '1');
                ini_set('display_startup_errors', '1');
            }
            return new Debug($isUse);
        });

        $hSL->add(Config::class, function () {
            $dir = '..' . DIRECTORY_SEPARATOR;
            $dir .= 'app' . DIRECTORY_SEPARATOR;
            $dir .= 'config' . DIRECTORY_SEPARATOR;
            return new Config(
                Config::getEnvFromFile($dir . '.env'),
                Config::getParamFromFile($dir . 'config.php')
            );
        });

        $hSL->add(BillingInterface::class, function () use ($hSL) {
            return (new Billing())
                ->setStorage(new MySQLStorage(
                    $hSL->getConfig()->get('db-billing.host'),
                    $hSL->getConfig()->get('db-billing.user'),
                    $hSL->getConfig()->get('db-billing.pass'),
                    $hSL->getConfig()->get('db-billing.name'),
                    intval($hSL->getConfig()->get('db-billing.port'))
                ));
        });

        $hSL->add(Dispatcher::class, function () use ($hSL) {
            return (new Dispatcher())
                ->addSubscriber(new PSCBSubscriber($hSL->getDebug()));
        });

        return $hSL;
    }
}