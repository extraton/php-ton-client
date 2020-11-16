<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\Params;

/**
 * Type FunctionHeader
 */
class FunctionHeader implements Params
{
    private ?string $pubKey;

    private ?int $time;

    private ?int $expire;

    /**
     * @param string|null $pubKey Public key used to sign message. Encoded with hex
     * @param int|null $time Message creation time in milliseconds
     * @param int|null $expire Message expiration time in seconds
     */
    public function __construct(?string $pubKey = null, ?int $time = null, ?int $expire = null)
    {
        $this->expire = $expire;
        $this->time = $time;
        $this->pubKey = $pubKey;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'pubkey' => $this->pubKey,
            'time'   => $this->time,
            'expire' => $this->expire,
        ];
    }
}
