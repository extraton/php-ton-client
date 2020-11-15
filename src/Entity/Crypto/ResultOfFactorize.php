<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfFactorize
 */
class ResultOfFactorize extends AbstractResult
{
    /**
     * @return array<string>
     */
    public function getFactors(): array
    {
        return $this->requireArray('factors');
    }
}
