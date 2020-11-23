<?php declare(strict_types=1);

namespace Advecs\App\Exception;

use Throwable;

class NotFoundException extends AbstractException
{
    public function __construct($message = 'страница не найдена', $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}