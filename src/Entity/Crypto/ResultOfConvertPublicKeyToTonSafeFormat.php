<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfConvertPublicKeyToTonSafeFormat extends AbstractResult
{
    public function getTonPublicKey(): string
    {
        return $this->requireString('ton_public_key');
    }
}
