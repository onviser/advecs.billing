<?php declare(strict_types=1);

namespace Advecs\App\Application;

use Advecs\App\Controller\Error\ErrorController;
use Advecs\App\HTTP\Request;
use Advecs\App\Router\Path;
use Advecs\App\Router\RouterBuilder;
use Advecs\App\ServiceLocator\ServiceLocatorBuilder;
use Advecs\Template\HTML\DebugTemplate;

/**
 * Class Application
 * @package Advecs\App\Application
 */
class Application
{
    /**
     * @param Request $hRequest
     * @return bool
     */
    public function handleRequest(Request $hRequest): bool
    {
        $hSL = ServiceLocatorBuilder::build();
        $hDebug = $hSL->getDebug()->add('start');

        $URI = $hRequest->get('REQUEST_URI', '/');
        $hPath = RouterBuilder::build($URI)->getPath() ?? new Path(ErrorController::class);
        $hResponse = $hSL->getController($hPath->getClass())
            ->setRequest($hRequest)
            ->getResponse();

        // вывод данных
        foreach ($hResponse->getHeaderAll() as $name => $value) {
            header($name . ($value === '' ? '' : ': ' . $value));
        }
        echo $hResponse->getData();

        // вывод отладочной информации
        if ($hDebug->isUse()) {
            $hDebug->add('количество файлов: ' . count(get_included_files()));
            $hDebug->add('stop');
            echo (new DebugTemplate($hDebug))->getData();
        }

        return true;
    }
}