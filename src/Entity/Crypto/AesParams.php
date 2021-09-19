<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\Params;

/**
 * Type AesParams
 */
class AesParams implements Params
{
    public const CIPHER_MODE_CBC = 'CBC';
    public const CIPHER_MODE_CFB = 'CFB';
    public const CIPHER_MODE_CTR = 'CTR';
    public const CIPHER_MODE_ECB = 'ECB';
    public const CIPHER_MODE_OFB = 'OFB';

    private string $cipherMode;
    private string $key;
    private ?string $iv;

    public function __construct(string $cipherMode, string $key, ?string $iv)
    {
        $this->cipherMode = $cipherMode;
        $this->key = $key;
        $this->iv = $iv;
    }

    public function jsonSerialize(): array
    {
        return [
            'mode' => $this->cipherMode,
            'key'  => $this->key,
            'iv'   => $this->iv,
        ];
    }
}
