<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfNaclSignDetached
 */
class ResultOfNaclSignDetached extends AbstractResult
{
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
