<?php declare(strict_types=1);

namespace Advecs\App\ServiceLocator;

use Advecs\App\Config\Config;
use Advecs\App\Debug\Debug;

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

        $hSL->add(Config::class, function () {
            $dir = '..' . DIRECTORY_SEPARATOR;
            $dir .= 'app' . DIRECTORY_SEPARATOR;
            $dir .= 'config' . DIRECTORY_SEPARATOR;
            return new Config(
                Config::getEnvFromFile($dir . '.env'),
                Config::getParamFromFile($dir . 'config.php')
            );
        });

        $hSL->add(Debug::class, function () use ($hSL) {
            return new Debug(
                boolval($hSL->getConfig()->get('app.debug'))
            );
        });

        if ($hSL->getDebug()->isUse()) {
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
        }

        return $hSL;
    }
}