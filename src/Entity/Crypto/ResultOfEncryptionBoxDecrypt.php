<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfEncryptionBoxDecrypt extends AbstractResult
{
    public function getData(): string
    {
        return $this->requireString('data');
    }
}
