<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\ParamsInterface;

class KeyPairParams implements ParamsInterface
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
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'public' => $this->public,
            'secret' => $this->secret,
        ];
    }
}
