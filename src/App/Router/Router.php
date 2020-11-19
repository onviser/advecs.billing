<?php declare(strict_types=1);

namespace Advecs\App\Router;

use Advecs\App\Exception\NotFoundException;

/**
 * Class Router
 * @package Advecs\App\Router
 */
class Router
{
    /** @var string */
    protected $URI = '';

    /** @var Path[] */
    protected $rule = [];

    /**
     * Router constructor.
     * @param string $URI
     */
    public function __construct(string $URI)
    {
        $this->URI = $URI;
    }

    /**
     * @param $URI
     * @param Path $hPath
     * @return $this
     */
    public function add($URI, Path $hPath): self
    {
        $this->rule[$this->getPatternURI($URI)] = $hPath;
        return $this;
    }

    /**
     * @return Path
     * @throws NotFoundException
     */
    public function getPath(): Path
    {
        foreach ($this->rule as $rule => $hPath) {
            $pattern = '/^' . $rule . '$/';
            if (preg_match($pattern, $this->URI, $match)) {
                return $hPath;
            }
        }
        throw new NotFoundException();
    }

    /**
     * @param string $URI
     * @return string
     */
    protected function getPatternURI(string $URI): string
    {
        $pattern = str_replace('/', '\/', $URI);
        $pattern = str_replace('-', '\-', $pattern);
        $pattern = str_replace('.', '\.', $pattern);
        $pattern = str_replace('?', '\?', $pattern);
        return $pattern;
    }
}