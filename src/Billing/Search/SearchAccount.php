<?php declare(strict_types=1);

namespace Advecs\Billing\Search;

/**
 * Поиск аккаунтов
 * Class SearchAccount
 * @package Advecs\Billing\Search
 */
class SearchAccount extends AbstractSearch
{
    /** @var int */
    protected $accountType = 0;

    /** @var int */
    protected $external = 0;

    /**
     * @param int $accountType
     * @return $this
     */
    public function setAccountType(int $accountType): self
    {
        $this->accountType = $accountType;
        return $this;
    }

    /** @return int */
    public function getAccountType(): int
    {
        return $this->accountType;
    }

    /**
     * @param int $external
     * @return $this
     */
    public function setExternal(int $external): self
    {
        $this->external = $external;
        return $this;
    }

    /** @return int */
    public function getExternal(): int
    {
        return $this->external;
    }
}