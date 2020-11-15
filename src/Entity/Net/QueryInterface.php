<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Params;

/**
 * Query interface
 */
interface QueryInterface
{
    /**
     * Get collection name
     *
     * @return string
     */
    public function getCollection(): string;

    /**
     * Get result fields
     *
     * @return string
     */
    public function getResult(): string;

    /**
     * Get query filters
     *
     * @return Params|null
     */
    public function getFilters(): ?Params;

    /**
     * Get OrderBy parameters
     *
     * @return Params|null
     */
    public function getOrderBy(): ?Params;

    /**
     * Get limit
     *
     * @return int|null
     */
    public function getLimit(): ?int;

    /**
     * Get timeout
     *
     * @return int|null
     */
    public function getTimeout(): ?int;
}
