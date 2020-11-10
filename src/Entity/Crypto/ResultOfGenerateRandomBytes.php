<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfGenerateRandomBytes extends AbstractResult
{
    public function getBase64(): string
    {
        return $this->requireString('bytes');
    }
}
