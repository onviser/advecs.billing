<?php declare(strict_types=1);

namespace Advecs\Template\HTML;

use Advecs\App\Debug\Debug;
use Advecs\Template\TemplateInterface;

class DebugTemplate implements TemplateInterface
{
    /** @var Debug */
    protected $hDebug;

    /**
     * DebugTemplate constructor.
     * @param Debug $hDebug
     */
    public function __construct(Debug $hDebug)
    {
        $this->hDebug = $hDebug;
    }

    /** @return string */
    public function getData(): string
    {
        $html = '';
        $html .= '<div id="debug">';
        foreach ($this->hDebug->getItems() as $item) {
            $html .= '<div class="debug-item">';
            $html .= $item['time'] . ' [' . $item['memory'] . '] ' . $item['text'];
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }
}