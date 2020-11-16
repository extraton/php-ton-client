<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfModularPower
 */
class ResultOfModularPower extends AbstractResult
{
    /**
     * Get result of modular exponentiation
     *
     * @return string
     */
    public function getModularPower(): string
    {
        return $this->requireString('modular_power');
    }
}
