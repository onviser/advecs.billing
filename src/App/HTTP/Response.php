<?php declare(strict_types=1);

namespace Advecs\App\HTTP;

/**
 * Class Response
 * @package Advecs\App\HTTP
 */
class Response
{
    const STATUS_OK = 1;
    const STATUS_ERROR = 0;

    /** @var array */
    protected $header = [];

    /** @var string */
    protected $data = '';

    /** @var int */
    protected $status = self::STATUS_OK;

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addHeader(string $name, string $value = ''): self
    {
        $this->header[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @param string $valueDefault
     * @return string
     */
    public function getHeader(string $name, string $valueDefault = '')
    {
        if ($name === '') {
            return '';
        }
        if (!isset($this->header[$name])) {
            return $valueDefault;
        }
        return $this->header[$name];
    }

    /** @return array */
    public function getHeaderAll(): array
    {
        return $this->header;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setData(string $data): self
    {
        $this->data = $data;
        return $this;
    }

    /** @return string */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /** @return int */
    public function getStatus(): int
    {
        return $this->status;
    }
}