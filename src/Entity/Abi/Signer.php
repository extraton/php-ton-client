<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\Crypto\KeyPair;
use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\DataException;

use function sprintf;

/**
 * Type Signer
 */
class Signer implements Params
{
    public const TYPE_NONE = 'None';

    public const TYPE_EXTERNAL = 'External';

    public const TYPE_KEYS = 'Keys';

    public const TYPE_SIGNING_BOX = 'SigningBox';

    private string $type;

    private string $publicKey;

    private KeyPair $keyPair;

    private int $signingBoxHandle;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Create with type none
     * No keys are provided. Creates an unsigned message
     *
     * @return self
     */
    public static function fromNone(): self
    {
        return new self(self::TYPE_NONE);
    }

    /**
     * Create from external public key
     *
     * @param string $publicKey Public key
     * @return self
     */
    public static function fromExternal(string $publicKey): self
    {
        $instance = new self(self::TYPE_EXTERNAL);
        $instance->setPublicKey($publicKey);

        return $instance;
    }

    /**
     * Create Signer from KeyPair
     *
     * @param KeyPair $keyPair Key pair
     * @return self
     */
    public static function fromKeys(KeyPair $keyPair): self
    {
        return self::fromKeyPair($keyPair);
    }

    /**
     * Create Signer from KeyPair
     *
     * @param KeyPair $keyPair Key pair
     * @return self
     */
    public static function fromKeyPair(KeyPair $keyPair): self
    {
        $instance = new self(self::TYPE_KEYS);
        $instance->setKeyPair($keyPair);

        return $instance;
    }

    /**
     * Set public key
     *
     * @param string $publicKey Public key
     * @return self
     */
    public function setPublicKey(string $publicKey): self
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Set key pair
     *
     * @param KeyPair $keyPair Key pair
     * @return self
     */
    public function setKeyPair(KeyPair $keyPair): self
    {
        $this->keyPair = $keyPair;

        return $this;
    }

    /**
     * Create Signer from signing box handle
     *
     * @param int $signingBoxHandle Signing box handle
     * @return self
     */
    public static function fromSigningBox(int $signingBoxHandle): self
    {
        $instance = new self(self::TYPE_SIGNING_BOX);
        $instance->setSigningBoxHandle($signingBoxHandle);

        return $instance;
    }

    /**
     * Set signing box handle
     *
     * @param int $signingBoxHandle Signing box handle
     * @return self
     */
    public function setSigningBoxHandle(int $signingBoxHandle): self
    {
        $this->signingBoxHandle = $signingBoxHandle;

        return $this;
    }

    /**
     * @inheritDoc
     */
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
            throw new DataException(sprintf('Unknown type %s.', $this->type));
        }

        return $result;
    }
}
