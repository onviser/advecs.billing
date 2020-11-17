<?php declare(strict_types=1);

namespace Advecs\App\URI;

/**
 * Class URI
 * @package Advecs\App\URI
 */
class URI
{
    /** @var string */
    protected $URI = '';

    /**
     * URI constructor.
     * @param string $URI
     */
    public function __construct(string $URI = '')
    {
        $this->URI = $URI;
        // parse_url($URI, PHP_URL_PATH);
        // parse_url($URI, PHP_URL_QUERY);
        // parse_url($URI, PHP_URL_FRAGMENT)
    }

    /** @return string */
    public function getUrl(): string
    {
        return $this->URI;
    }
}