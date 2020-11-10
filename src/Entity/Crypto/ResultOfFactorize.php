<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfFactorize extends AbstractResult
{
    public function getFactors(): array
    {
        return $this->requireArray('factors');
    }
}
