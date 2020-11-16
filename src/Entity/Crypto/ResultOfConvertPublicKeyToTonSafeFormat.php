<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfConvertPublicKeyToTonSafeFormat
 */
class ResultOfConvertPublicKeyToTonSafeFormat extends AbstractResult
{
    /**
     * Get public key represented in TON safe format
     *
     * @return string
     */
    public function getTonPublicKey(): string
    {
        return $this->requireString('ton_public_key');
    }
}
