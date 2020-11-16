<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfScrypt
 */
class ResultOfScrypt extends AbstractResult
{
    /**
     * Get derived key. Encoded with hex.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->requireString('key');
    }
}
