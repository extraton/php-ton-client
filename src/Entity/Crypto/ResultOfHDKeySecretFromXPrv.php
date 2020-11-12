<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfHDKeySecretFromXPrv extends AbstractResult
{
    public function getSecret(): string
    {
        return $this->requireString('secret');
    }
}
