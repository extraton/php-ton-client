<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Boc;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfEncodeBoc
 */
class ResultOfEncodeBoc extends AbstractResult
{
    /**
     * Get encoded cell BOC or BOC cache key
     *
     * @return string
     */
    public function getBoc(): string
    {
        return $this->requireString('boc');
    }
}
