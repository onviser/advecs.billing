<?php declare(strict_types=1);

namespace Advecs\App\Router;

/**
 * Class Path
 * @package Advecs\App\Router
 */
class Path
{
    /** @var string */
    protected $class = '';

    /**
     * Path constructor.
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /** @return string */
    public function getClass(): string
    {
        return $this->class;
    }
}