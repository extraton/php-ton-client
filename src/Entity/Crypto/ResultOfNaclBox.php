<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfNaclBox extends AbstractResult
{
    public function getEncrypted(): string
    {
        return $this->requireString('encrypted');
    }
}
