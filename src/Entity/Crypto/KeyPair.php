<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\Params;

/**
 * Key pair
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

    public function getPublic(): string
    {
        return $this->public;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function jsonSerialize(): array
    {
        return [
            'public' => $this->getPublic(),
            'secret' => $this->getSecret(),
        ];
    }
}
