<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfSigningBoxGetPublicKey
 */
class ResultOfSigningBoxGetPublicKey extends AbstractResult
{
    /**
     * Returns public key of signing box (encoded with hex)
     *
     * @return string
     */
    public function getPublic(): string
    {
        return $this->requireString('pubkey');
    }
}
