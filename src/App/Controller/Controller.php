<?php declare(strict_types=1);

namespace Advecs\App\Controller;

use Advecs\App\Config\Config;
use Advecs\App\Debug\Debug;
use Advecs\App\HTTP\Request;
use Advecs\App\HTTP\Response;

/**
 * Class Controller
 * @package Advecs\App\Controller
 */
abstract class Controller
{
    /** @var Request */
    protected $hRequest;

    /** @var Config */
    protected $hConfig;

    /** @var Debug */
    protected $hDebug;

    /**
     * @param Request $hRequest
     * @return $this
     */
    public function setRequest(Request $hRequest): self
    {
        $this->hRequest = $hRequest;
        return $this;
    }

    /**
     * @param Config $hConfig
     * @return $this
     */
    public function setConfig(Config $hConfig): self
    {
        $this->hConfig = $hConfig;
        return $this;
    }

    /**
     * @param Debug $hDebug
     * @return $this
     */
    public function setDebug(Debug $hDebug): self
    {
        $this->hDebug = $hDebug;
        return $this;
    }

    /** @return Response */
    public function getResponse(): Response
    {
        return (new Response())
            ->setStatus(Response::STATUS_OK)
            ->addHeader('Content-Type', 'text/html; charset=utf-8')
            ->addHeader('Cache-Control', 'no-cache, must-revalidate')
            ->addHeader('Pragma', 'no-cache')
            ->addHeader('X-Frame-Options', 'SAMEORIGIN')
            ->addHeader('X-Content-Type-Options', 'nosniff')
            ->addHeader('X-XSS-Protection', '1; mode=block')
            ->setData('');
    }
}