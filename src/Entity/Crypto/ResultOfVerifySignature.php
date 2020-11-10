<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfVerifySignature extends AbstractResult
{
    public function getUnsigned(): string
    {
        return $this->requireString('unsigned');
    }
}
