<?php declare(strict_types=1);

namespace Advecs\App\Debug;

/**
 * Class Debug
 * @package Advecs\App\Debug
 */
class Debug
{
    /**
     * array of messages
     * @var array
     */
    private $items = [];

    /**
     * script working time
     * @var float
     */
    private $timeStart = 0.0;

    /**
     * script memory usage
     * @var integer
     */
    private $memoryUsage = 0;

    /** @var boolean */
    private $isUse = false;

    /**
     * Debug constructor.
     * @param bool $isUse
     */
    public function __construct(bool $isUse = false)
    {
        $this->timeStart = $this->getCurrentTime();
        $this->memoryUsage = memory_get_usage();
        $this->isUse = $isUse;
    }

    /**
     * @param string $message message text
     * @return $this
     */
    public function add(string $message)
    {
        if (!$this->isUse) {
            return $this;
        }
        $this->items[] = [
            'time'   => $this->getDuration(),
            'text'   => $message,
            'memory' => $this->getMemoryUsage()
        ];
        return $this;
    }

    /**
     * return current time
     * @return float
     */
    protected function getCurrentTime(): float
    {
        return round(microtime(true), 2);
    }

    /**
     * return array of messages
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * return script duration
     * @return float
     */
    public function getDuration(): float
    {
        return round($this->getCurrentTime() - $this->timeStart, 2);
    }

    /**
     * return script memory usage
     * @return integer
     */
    public function getMemoryUsage(): int
    {
        $result = memory_get_usage() - $this->memoryUsage;
        return intval(round($result / 1024));
    }

    /** @return bool */
    public function isUse(): bool
    {
        return $this->isUse;
    }
}