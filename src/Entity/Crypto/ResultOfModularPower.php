<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfModularPower extends AbstractResult
{
    public function getModularPower(): string
    {
        return $this->requireString('modular_power');
    }
}
