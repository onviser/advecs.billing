<?php declare(strict_types=1);

namespace Advecs\App\Application;

use Advecs\App\Controller\Error\FactoryErrorController;
use Advecs\App\Debug\Debug;
use Advecs\App\HTTP\Request;
use Advecs\App\HTTP\Response;
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

        try {
            $hPath = RouterBuilder::build($URI)->getPath();
            $hResponse = $hSL->getController($hPath->getClass())
                ->setRequest($hRequest)
                ->getResponse();
        }
        catch (\Throwable $hException) {
            $hResponse =
                $hSL->getController(FactoryErrorController::getControllerClass($hException), [$hException])
                    ->setRequest($hRequest)
                    ->getResponse();
        }
        echo $this->output($hResponse, $hDebug);
        return true;
    }

    /**
     * @param Response $hResponse
     * @param Debug $hDebug
     * @return string
     */
    protected function output(Response $hResponse, Debug $hDebug): string
    {
        // вывод данных
        foreach ($hResponse->getHeaderAll() as $name => $value) {
            header($name . ($value === '' ? '' : ': ' . $value));
        }
        $output = $hResponse->getData();

        // вывод отладочной информации
        if ($hDebug->isUse()) {
            $hDebug->add('количество файлов: ' . count(get_included_files()));
            $hDebug->add('stop');
            $output .= (new DebugTemplate($hDebug))->getData();
        }

        return $output;
    }
}