<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\Crypto\KeyPair;
use Extraton\TonClient\Entity\ParamsInterface;
use LogicException;

class SignerParams implements ParamsInterface
{
    public const TYPE_NONE = 'None';

    public const TYPE_EXTERNAL = 'External';

    public const TYPE_KEYS = 'Keys';

    public const TYPE_SIGNING_BOX = 'SigningBox';

    private string $type;

    private string $publicKey;

    private KeyPair $keyPair;

    private int $signingBoxHandle;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function fromNone(): self
    {
        return new self(self::TYPE_NONE);
    }

    public static function fromExternal(string $publicKey): self
    {
        $instance = new self(self::TYPE_EXTERNAL);
        $instance->setPublicKey($publicKey);

        return $instance;
    }

    public static function fromKeys(KeyPair $keyPair): self
    {
        $instance = new self(self::TYPE_KEYS);
        $instance->setKeyPair($keyPair);

        return $instance;
    }

    public static function fromSigningBox(int $signingBoxHandle): self
    {
        $instance = new self(self::TYPE_SIGNING_BOX);
        $instance->setSigningBoxHandle($signingBoxHandle);

        return $instance;
    }

    public function setPublicKey(string $publicKey): self
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    public function setKeyPair(KeyPair $keyPair): self
    {
        $this->keyPair = $keyPair;

        return $this;
    }

    public function setSigningBoxHandle(int $signingBoxHandle): self
    {
        $this->signingBoxHandle = $signingBoxHandle;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $result['type'] = $this->type;

        if ($this->type === self::TYPE_NONE) {
            return $result;
        }

        if ($this->type === self::TYPE_EXTERNAL) {
            $result['public_key'] = $this->publicKey;
        } elseif ($this->type === self::TYPE_KEYS) {
            $result['keys'] = $this->keyPair;
        } elseif ($this->type === self::TYPE_SIGNING_BOX) {
            $result['handle'] = $this->signingBoxHandle;
        } else {
            throw new LogicException('Unknown type.');
        }

        return $result;
    }
}
