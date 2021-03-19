<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Utils;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfCalcStorageFee
 */
class ResultOfCalcStorageFee extends AbstractResult
{
    /**
     * Get fee
     *
     * @return string
     */
    public function getFee(): string
    {
        return $this->requireString('fee');
    }
}
