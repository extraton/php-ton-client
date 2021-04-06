<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Utils;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfCompressZstd
 */
class ResultOfCompressZstd extends AbstractResult
{
    /**
     * Get compressed data. Must be encoded as base64.
     *
     * @return string
     */
    public function getCompressed(): string
    {
        return $this->requireString('compressed');
    }
}
