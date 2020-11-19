<?php declare(strict_types=1);

namespace Advecs\App\Controller\Error;

use Advecs\App\Controller\Controller;
use Throwable;

abstract class ErrorController extends Controller
{
    /** @var Throwable */
    protected $hException;

    /**
     * ErrorController constructor.
     * @param Throwable $hException
     */
    public function __construct(Throwable $hException)
    {
        $this->hException = $hException;
    }
}