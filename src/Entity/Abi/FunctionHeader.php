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
     * @param string|null $pubKey
     * @param int|null $time
     * @param int|null $expire
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
