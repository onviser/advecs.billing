<?php declare(strict_types=1);

namespace Advecs\App\ServiceLocator;

use Advecs\App\Config\Config;
use Advecs\App\Controller\Controller;
use Advecs\App\Controller\Error\InternalErrorController;
use Advecs\App\Debug\Debug;
use Closure;
use ReflectionClass;

/**
 * Class ServiceLocator
 * @package Advecs\App\ServiceLocator
 */
class ServiceLocator
{
    /** @var array */
    protected $closure = [];

    /** @var Config */
    protected $hConfig;

    /** @var Debug */
    protected $hDebug;

    /**
     * @param $class
     * @param Closure $hClosure
     * @return $this
     */
    public function add(string $class, Closure $hClosure): self
    {
        $this->closure[$class] = $hClosure;
        return $this;
    }

    /** @return Config */
    public function getConfig(): Config
    {
        if (!$this->hConfig) {
            $this->hConfig = $this->closure[Config::class]();
        }
        return $this->hConfig;
    }

    /** @return Debug */
    public function getDebug(): Debug
    {
        if (!$this->hDebug) {
            $this->hDebug = $this->closure[Debug::class]();
        }
        return $this->hDebug;
    }

    /**
     * @param string $class
     * @param array $param
     * @return Controller
     */
    public function getController(string $class = '', array $param = []): Controller
    {
        /** @var Controller $hController */

        try {
            $hR = new ReflectionClass($class);
            if (!$hR->getConstructor() || (count($hR->getConstructor()->getParameters()) == 0)) {
                $hController = $hR->newInstance();
            }
            else {
                $hController = $hR->newInstanceArgs(
                    $this->getControllerParam($hR->getConstructor()->getParameters(), $param)
                );
            }
        }
        catch (\Throwable $hThrowable) {
            $hController = new InternalErrorController($hThrowable);
        }

        return $hController
            ->setConfig($this->getConfig())
            ->setDebug($this->getDebug());
    }

    /**
     * @param array $paramConstructor
     * @param array $param
     * @return array
     */
    public function getControllerParam(array $paramConstructor, array $param = [])
    {
        $result = [];
        foreach ($paramConstructor as $hParam) {
            if ($hParam instanceof \ReflectionParameter) {
                if ($hParam->getClass()) {
                    $hInstance = $this->getInstance($hParam->getClass()->getName());
                    if ($hInstance) {
                        $result[] = $hInstance;
                    }
                    elseif (array_key_exists($hParam->getPosition(), $param)) {
                        $result[] = $param[$hParam->getPosition()];
                    }
                    else {
                        $result[] = null;
                    }
                }
                else {
                    if (array_key_exists($hParam->getName(), $param)) {
                        $result[] = $param[$hParam->getName()];
                    }
                    elseif (array_key_exists($hParam->getPosition(), $param)) {
                        $result[] = $param[$hParam->getPosition()];
                    }
                    else {
                        $result[] = null;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param string $class
     * @return |null
     */
    public function getInstance(string $class)
    {
        switch ($class) {
            default:
                if (isset($this->closure[$class])) {
                    return $this->closure[$class]();
                }
                break;
        }
        return null;
    }
}