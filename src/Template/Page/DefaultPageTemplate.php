<?php declare(strict_types=1);

namespace Advecs\Template\Page;

class DefaultPageTemplate extends PageTemplate
{
    /** @return string */
    protected function getContent(): string
    {
        return 'Биллинг компании &laquo;Адвекс&raquo;';
    }
}