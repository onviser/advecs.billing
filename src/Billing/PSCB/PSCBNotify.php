<?php declare(strict_types=1);

namespace Advecs\Billing\PSCB;

/**
 * Class PSCBNotify
 * @package Advecs\Billing\PSCB
 */
class PSCBNotify
{
    /** @var int */
    protected $id = 0;

    /** @var string */
    protected $raw = '';

    /** @var string */
    protected $json = '';

    /**
     * PSCBNotify constructor.
     * @param string $raw (передается в base64)
     * @param string $json (передается в base64)
     */
    public function __construct(string $raw, string $json)
    {
        $this->raw = $raw;
        $this->json = $json;
    }

    /** @return string */
    public function getRaw(): string
    {
        return $this->raw;
    }

    /** @return string */
    public function getJSON(): string
    {
        return $this->json;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /** @return int */
    public function getId(): int
    {
        return $this->id;
    }

    /** @return PSCBOrder[] */
    public function getOrders(): array
    {
        $jsonAsString = base64_encode($this->json);
        $json = json_decode($jsonAsString, true);
        if (!isset($json['payments'])) {
            return [];
        }

        $orders = [];
        foreach ($json['payments'] as $item) {
            $orderId = intval($item['orderId']);
            $account = intval($item['account']);
            $amount = floatval($item['amount']);
            $state = strval($item['state']);
            $paymentMethod = strval($item['paymentMethod']);
            $paymentJSON = json_encode($item);

            $orders[] = new PSCBOrder($orderId, $account, $amount, $state, $paymentMethod, $paymentJSON);
        }
        return $orders;
    }
}