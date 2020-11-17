<?php declare(strict_types=1);

namespace Advecs\App\HTTP;

/**
 * Class Request
 * @package Advecs\App\HTTP
 */
class Request
{
    /** @var array */
    protected $data = [];

    /**
     * Request constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function set(string $key, $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function fill(string $key, $value): self
    {
        if (array_key_exists($key, $this->data)) {
            $this->data[$key] = $value;
        }
        return $this;
    }

    /**
     * @param $key
     * @param string $value
     * @return string|array
     */
    public function get(string $key, $value = '')
    {
        return $this->data[$key] ?? $value;
    }

    /** @return array */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isExists(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }
}