<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

/**
 * Sign keys
 */
class KeyPair
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

    public function toArray(): array
    {
        return [
            'public' => $this->public,
            'secret' => $this->secret,
        ];
    }

    public function getPublic(): string
    {
        return $this->public;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}
