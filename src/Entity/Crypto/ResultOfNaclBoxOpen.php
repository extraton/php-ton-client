<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfNaclBoxOpen
 */
class ResultOfNaclBoxOpen extends AbstractResult
{
    /**
     * Get decrypted data encoded in base64
     *
     * @return string
     */
    public function getDecrypted(): string
    {
        return $this->requireString('decrypted');
    }
}
