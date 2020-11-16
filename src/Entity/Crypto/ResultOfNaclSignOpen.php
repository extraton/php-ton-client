<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfNaclSignOpen
 */
class ResultOfNaclSignOpen extends AbstractResult
{
    /**
     * Get unsigned data, encoded in base64
     *
     * @return string
     */
    public function getUnsigned(): string
    {
        return $this->requireString('unsigned');
    }
}
