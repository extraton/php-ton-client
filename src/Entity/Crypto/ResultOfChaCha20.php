<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfChaCha20
 */
class ResultOfChaCha20 extends AbstractResult
{
    /**
     * Get encrypted / decrypted data. Encoded with base64.
     *
     * @return string
     */
    public function getData(): string
    {
        return $this->requireString('data');
    }
}
