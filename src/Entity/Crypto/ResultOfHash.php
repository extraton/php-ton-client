<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfHash
 */
class ResultOfHash extends AbstractResult
{
    /**
     * Get hash of input data. Encoded with 'hex'.
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->requireString('hash');
    }
}
