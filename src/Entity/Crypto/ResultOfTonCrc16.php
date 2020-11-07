<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfTonCrc16 extends AbstractResult
{
    public function getCrc(): int
    {
        return $this->requireInt('crc');
    }
}
