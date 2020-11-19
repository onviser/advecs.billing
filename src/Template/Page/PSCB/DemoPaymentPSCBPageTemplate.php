<?php declare(strict_types=1);

namespace Advecs\Template\Page\PSCB;

use Advecs\Template\Page\PageTemplate;

/**
 * Class DemoPSCBPageTemplate
 * @package Advecs\Template\Page\PSCB
 */
class DemoPaymentPSCBPageTemplate extends PageTemplate
{
    /** @var string */
    protected $url = '';

    /** @var string */
    protected $marketPlace = '';

    /** @var string */
    protected $message = '';

    /** @var string */
    protected $signature = '';

    /**
     * DemoPaymentPSCBPageTemplate constructor.
     * @param string $url
     * @param string $marketPlace
     * @param string $message
     * @param string $signature
     */
    public function __construct(string $url, string $marketPlace, string $message, string $signature)
    {
        $this->url = $url;
        $this->marketPlace = $marketPlace;
        $this->message = $message;
        $this->signature = $signature;
    }

    /** @return string */
    protected function getContent(): string
    {
        $html = '';
        $html .= '<form method="post" action="' . $this->url . '">';
        $html .= '<input type="hidden" name="marketPlace" value="' . $this->marketPlace . '" />';
        $html .= '<input type="hidden" name="message" value="' . $this->message . '" />';
        $html .= '<input type="hidden" name="signature" value="' . $this->signature . '" />';
        $html .= '<button>Перейти к оплате на сайт ПСКБ</button>';
        $html .= '</form>';
        return $html;
    }
}