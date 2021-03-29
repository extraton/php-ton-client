<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfNaclSignDetachedVerify
 */
class ResultOfNaclSignDetachedVerify extends AbstractResult
{
    /**
     * Returns true if verification succeeded or false if it failed
     *
     * @return bool
     */
    public function getSucceeded(): bool
    {
        return $this->requireBool('succeeded');
    }
}
