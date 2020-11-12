<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfNaclSignOpen extends AbstractResult
{
    public function getUnsigned(): string
    {
        return $this->requireString('unsigned');
    }
}
