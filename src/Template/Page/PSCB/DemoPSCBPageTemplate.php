<?php declare(strict_types=1);

namespace Advecs\Template\Page\PSCB;

use Advecs\Template\Page\PageTemplate;

/**
 * Class DemoPSCBPageTemplate
 * @package Advecs\Template\Page\PSCB
 */
class DemoPSCBPageTemplate extends PageTemplate
{
    /** @return string */
    protected function getContent(): string
    {
        $html = '<h1>Тестовый платеж</h1>';

        $html .= '<p>';
        $html .= 'номер счета: 1<br />';
        $html .= 'сумма платежа: 1 руб.<br />';
        $html .= 'тип оплаты: банковская карта (ac)<br />';
        $html .= '</p>';

        $html .= '<form id="payment-form" name="payment-form" method="post" action="/demo.html">';
        $html .= '<input type="hidden" name="payment" value="1" />';
        $html .= '<button>Перейти к оплате</button>';
        $html .= '</form>';

        return $html;
    }
}