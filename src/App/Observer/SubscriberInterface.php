<?php declare(strict_types=1);

namespace Advecs\App\Observer;

use Closure;

/**
 * Interface SubscriberInterface
 * @package Advecs\App\Observer
 */
interface SubscriberInterface
{
    /** @return Closure[] */
    public function getEvent(): array;
}