<?php declare(strict_types=1);

namespace Advecs\App\Controller\Error;

use Advecs\Billing\Exception\MySQLException;
use Throwable;

class MySQLErrorController extends InternalErrorController
{
    /**
     * @param Throwable $hException
     * @return string
     */
    protected function getMessage(Throwable $hException)
    {
        /** @var MySQLException $hException */
        $message = 'Ошибка базы данных: ' . $hException->getError() . PHP_EOL;
        $message .= 'Код ошибки: ' . $hException->getErrorNumber() . PHP_EOL;
        $message .= 'SQL: ' . $hException->getSQL() . PHP_EOL;
        return $message;
    }
}