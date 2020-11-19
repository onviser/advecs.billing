<?php declare(strict_types=1);

namespace Advecs\Template\Page;

use Advecs\Template\TemplateInterface;

/**
 * Class PageTemplate
 * @package Advecs\Template\Page
 */
abstract class PageTemplate implements TemplateInterface
{
    /** @return string */
    public function getData(): string
    {
        $html = '<!doctype html><html lang="ru">';
        $html .= $this->getHeader();
        $html .= '<body>';
        $html .= $this->getContent();
        $html .= $this->getFooter();
        $html .= '</body>';
        $html .= '</html>';
        return $html;
    }

    /** @return string */
    abstract protected function getContent(): string;

    /** @return string */
    protected function getHeader(): string
    {
        $html = '';
        $html .= '<head>';
        $html .= '<title>' . $this->getTitle() . '</title>';
        $html .= '</head>';
        return $html;
    }

    /** @return string */
    protected function getFooter(): string
    {
        $html = '<footer>';
        $html .= '&copy; ' . date('Y') . ' &laquo;Адвекс&raquo;';
        $html .= '</footer>';
        return $html;
    }

    /** @return string */
    protected function getTitle(): string
    {
        return 'Адвекс';
    }
}