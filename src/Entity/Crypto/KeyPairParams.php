<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\ParamsInterface;

class KeyPairParams implements ParamsInterface
{
    private string $public;

    private string $private;

    /**
     * @param string $public Public key - 64 symbols hex string
     * @param string $private Private key - u64 symbols hex string
     */
    public function __construct(string $public, string $private)
    {
        $this->public = $public;
        $this->private = $private;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'public'  => $this->public,
            'private' => $this->private,
        ];
    }
}
