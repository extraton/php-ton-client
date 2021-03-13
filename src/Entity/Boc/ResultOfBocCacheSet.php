<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Boc;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfBocCacheSet
 */
class ResultOfBocCacheSet extends AbstractResult
{
    /**
     * Get reference to the cached BOC
     *
     * @return string
     */
    public function getBocRef(): string
    {
        return $this->requireString('boc_ref');
    }
}
