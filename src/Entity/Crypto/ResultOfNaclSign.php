<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfNaclSign
 */
class ResultOfNaclSign extends AbstractResult
{
    /**
     * Get signed data, encoded in base64
     *
     * @return string
     */
    public function getSigned(): string
    {
        return $this->requireString('signed');
    }
}
