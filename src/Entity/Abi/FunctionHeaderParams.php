<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\Params;

class FunctionHeaderParams implements Params
{
    private ?int $expire;

    private ?int $time;

    private ?string $pubKey;

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
