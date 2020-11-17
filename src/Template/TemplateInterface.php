<?php declare(strict_types=1);

namespace Advecs\Template;

interface TemplateInterface
{
    /** @return string */
    public function getData(): string;
}