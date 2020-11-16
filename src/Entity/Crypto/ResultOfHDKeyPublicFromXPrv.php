<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfHDKeyPublicFromXPrv
 */
class ResultOfHDKeyPublicFromXPrv extends AbstractResult
{
    /**
     * Get public key - 64 symbols hex string
     *
     * @return string
     */
    public function getPublic(): string
    {
        return $this->requireString('public');
    }
}
