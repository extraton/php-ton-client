<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfEncodeAccount
 */
class ResultOfEncodeAccount extends AbstractResult
{
    /**
     * Get account BOC encoded in base64
     *
     * @return string
     */
    public function getAccount(): string
    {
        return $this->requireString('account');
    }

    /**
     * Get account ID encoded in hex
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->requireString('id');
    }
}
