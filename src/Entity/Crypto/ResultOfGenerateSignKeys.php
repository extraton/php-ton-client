<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfGenerateSignKeys
 */
class ResultOfGenerateSignKeys extends AbstractResult
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

    /**
     * Get private key - u64 symbols hex string
     *
     * @return string
     */
    public function getSecret(): string
    {
        return $this->requireString('secret');
    }

    /**
     * Get KeyPair
     *
     * @return KeyPair
     */
    public function getKeyPair(): KeyPair
    {
        return new KeyPair(
            $this->requireString('public'),
            $this->requireString('secret')
        );
    }
}
