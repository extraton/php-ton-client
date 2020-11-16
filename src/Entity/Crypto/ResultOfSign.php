<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfSign
 */
class ResultOfSign extends AbstractResult
{
    /**
     * Get signed data combined with signature encoded in base64
     *
     * @return string
     */
    public function getSigned(): string
    {
        return $this->requireString('signed');
    }

    /**
     * Get signature encoded in hex
     *
     * @return string
     */
    public function getSignature(): string
    {
        return $this->requireString('signature');
    }
}
