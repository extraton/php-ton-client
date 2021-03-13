<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Boc;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfBocCacheGet
 */
class ResultOfBocCacheGet extends AbstractResult
{
    /**
     * Get BOC encoded as base64
     *
     * @return string|null
     */
    public function getBoc(): ?string
    {
        return $this->getOriginData('boc');
    }
}
