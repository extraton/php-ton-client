<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\ParamsInterface;

class FunctionHeaderParams implements ParamsInterface
{
    private ?int $expire;

    private ?int $time;

    private ?string $pubKey;

    /**
     * @param int|null $expire
     * @param int|null $time
     * @param string|null $pubKey
     */
    public function __construct(?int $expire = null, ?int $time = null, ?string $pubKey = null)
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
            'expire' => $this->expire,
            'time'   => $this->time,
            'pubkey' => $this->pubKey,
        ];
    }
}
