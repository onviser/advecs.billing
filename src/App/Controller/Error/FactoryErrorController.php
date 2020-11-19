<?php declare(strict_types=1);

namespace Advecs\App\Controller\Error;

use Advecs\App\Exception\NotFoundException;
use Advecs\Billing\Exception\BillingException;
use Throwable;

class FactoryErrorController
{
    /**
     * @param Throwable $hException
     * @return string
     */
    public static function getControllerClass(Throwable $hException): string
    {
        switch (get_class($hException)) {
            case  NotFoundException::class;
                return NotFoundErrorController::class;
            case BillingException::class:
                break;
        }
        return InternalErrorController::class;
    }
}