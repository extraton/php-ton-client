<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfTonCrc16
 */
class ResultOfTonCrc16 extends AbstractResult
{
    /**
     * Get calculated CRC for input data
     *
     * @return int
     */
    public function getCrc(): int
    {
        return $this->requireInt('crc');
    }
}
