<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\Params;

class EncryptionAlgorithm implements Params
{
    const TYPE_AES = 'AES';

    private string $type;

    private AesParams $aesParams;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function createFromAES(AesParams $aesParams): self
    {
        $instance = new self(self::TYPE_AES);
        $instance->setAesParams($aesParams);

        return $instance;
    }

    public function setAesParams(AesParams $aesParams): self
    {
        $this->aesParams = $aesParams;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        if ($this->type === self::TYPE_AES) {
            $params['type'] = $this->type;
            $params['value'] = $this->aesParams;

            return $params;
        }

        return [];
    }
}
