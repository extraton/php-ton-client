<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Utils;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfDecompressZstd
 */
class ResultOfDecompressZstd extends AbstractResult
{
    /**
     * Get decompressed data. Must be encoded as base64.
     *
     * @return string
     */
    public function getDecompressed(): string
    {
        return $this->requireString('decompressed');
    }
}
