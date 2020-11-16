<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfHDKeySecretFromXPrv
 */
class ResultOfHDKeySecretFromXPrv extends AbstractResult
{
    /**
     * Get private key - 64 symbols hex string
     *
     * @return string
     */
    public function getSecret(): string
    {
        return $this->requireString('secret');
    }
}
