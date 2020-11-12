<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfNaclSignDetached extends AbstractResult
{
    public function getSignature(): string
    {
        return $this->requireString('signature');
    }
}
