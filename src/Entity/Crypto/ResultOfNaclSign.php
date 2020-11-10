<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfNaclSign extends AbstractResult
{
    public function getKey(): string
    {
        return $this->requireString('signed');
    }
}
