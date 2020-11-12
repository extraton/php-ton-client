<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfNaclBoxOpen extends AbstractResult
{
    public function getDecrypted(): string
    {
        return $this->requireString('decrypted');
    }
}
