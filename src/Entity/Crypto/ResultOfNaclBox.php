<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfNaclBox
 */
class ResultOfNaclBox extends AbstractResult
{
    /**
     * Get encrypted data encoded in base64
     *
     * @return string
     */
    public function getEncrypted(): string
    {
        return $this->requireString('encrypted');
    }
}
