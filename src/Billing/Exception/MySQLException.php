<?php declare(strict_types=1);

namespace Advecs\Billing\Exception;

use Exception;

/**
 * Class StorageException
 * @package Advecs\Billing\Exception
 */
class MySQLException extends Exception
{
    /** @var int */
    protected $errorNumber = 0;

    /** @var string */
    protected $error = '';

    /** @var string */
    protected $sql = '';

    /**
     * @param int $errorNumber
     * @return $this
     */
    public function setErrorNumber(int $errorNumber): self
    {
        $this->errorNumber = $errorNumber;
        return $this;
    }

    /** @return int */
    public function getErrorNumber(): int
    {
        return $this->errorNumber;
    }

    /**
     * @param string $error
     * @return $this
     */
    public function setError(string $error): self
    {
        $this->error = $error;
        return $this;
    }

    /** @return string */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $sql
     * @return $this
     */
    public function setSQL(string $sql): self
    {
        $this->sql = $sql;
        return $this;
    }

    /** @return string */
    public function getSQL(): string
    {
        return $this->sql;
    }
}