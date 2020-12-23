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
     * @param string $raw
     * @param string $json
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
}