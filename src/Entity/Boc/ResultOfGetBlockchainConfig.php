<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Boc;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfGetBlockchainConfig
 */
class ResultOfGetBlockchainConfig extends AbstractResult
{
    /**
     * Get blockchain config BOC encoded as base64
     *
     * @return string
     */
    public function getConfigBoc(): string
    {
        return $this->requireString('config_boc');
    }
}
