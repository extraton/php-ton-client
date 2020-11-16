<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\Params;

/**
 * Type KeyPair
 */
class KeyPair implements Params
{
    private string $public;

    private string $secret;

    /**
     * @param string $public Public key - 64 symbols hex string
     * @param string $secret Private key - u64 symbols hex string
     */
    public function __construct(string $public, string $secret)
    {
        $this->public = $public;
        $this->secret = $secret;
    }

    /**
     * Get public key - 64 symbols hex string
     *
     * @return string
     */
    public function getPublic(): string
    {
        return $this->public;
    }

    /**
     * Get private key - u64 symbols hex string
     *
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'public' => $this->getPublic(),
            'secret' => $this->getSecret(),
        ];
    }
}
